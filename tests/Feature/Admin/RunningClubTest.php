<?php

// tests/Feature/Admin/RunningClubTest.php

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

beforeEach(fn () => $this->actingAs(User::factory()->admin()->create()));

test('calculates expected attendance from RSVPs and extras', function () {
    $event = Event::factory()->create();
    Rsvp::factory()->create(['event_id' => $event->id, 'extras' => 2]);
    Rsvp::factory()->create(['event_id' => $event->id, 'extras' => 0]);

    $e = Event::withCount('rsvps')->withSum('rsvps', 'extras')->find($event->id);

    expect($e->rsvps_count + $e->rsvps_sum_extras)->toBe(4);
});

test('exports RSVPs as CSV', function () {
    $event = Event::factory()->create();
    Rsvp::factory()->count(3)->create(['event_id' => $event->id]);

    $this->get(route('admin.events.rsvps.export', $event))
        ->assertOk()
        ->assertDownload("event-{$event->id}-rsvps.csv");
});

test('will not delete an upcoming event that members have RSVPd to', function () {
    $event = Event::factory()->create(['event_date' => today()->addDays(3)]);
    Rsvp::factory()->create(['event_id' => $event->id]);

    $this->delete(route('admin.events.destroy', $event))->assertRedirect();

    expect(Event::find($event->id))->not->toBeNull();
});

test('adds a run with a title and distance', function () {
    $this->post(route('admin.events.store'), [
        'type' => 'run', 'title' => 'Saturday 5 km', 'event_date' => today()->addWeek()->toDateString(),
        'event_time' => '06:30', 'address' => 'Live Cafe, Main Road', 'distance_km' => 5,
    ])->assertRedirect(route('admin.events.index'));

    expect(Event::where('title', 'Saturday 5 km')->first())
        ->type->toBe('run')
        ->distance_km->toEqual(5.0);
});

test('cancels a run but keeps its RSVPs', function () {
    Notification::fake();
    $event = Event::factory()->create(['event_date' => today()->addDays(3)]);
    Rsvp::factory()->create(['event_id' => $event->id]);

    $this->patch(route('admin.events.cancel', $event))->assertRedirect(route('admin.events.index'));

    expect($event->fresh()->isCancelled())->toBeTrue()
        ->and($event->rsvps()->count())->toBe(1);
});

test('counts only RSVPs that are still going', function () {
    $event = Event::factory()->create(['event_date' => today()->addDays(3)]);
    Rsvp::factory()->create(['event_id' => $event->id, 'extras' => 1]);
    Rsvp::factory()->cancelled()->create(['event_id' => $event->id, 'extras' => 3]);

    $this->get(route('admin.events.index'))
        ->assertOk()
        ->assertViewHas('events', fn ($events) => $events->first()->going_count === 1);
});

test('publishes an announcement', function () {
    $this->post(route('admin.announcements.store'), [
        'title' => 'Earlier start', 'description' => 'Saturday run moves to 6am', 'action' => 'publish',
    ])->assertRedirect(route('admin.announcements.index'));

    expect(Announcement::where('title', 'Earlier start')->first()->isPublished())->toBeTrue();
});

test('keeps a draft announcement private', function () {
    $this->post(route('admin.announcements.store'), [
        'title' => 'Not yet', 'description' => 'Route still being planned', 'action' => 'draft',
    ]);

    expect(Announcement::where('title', 'Not yet')->first()->published_at)->toBeNull();
});

test('folds the new announcement form away until it is needed', function () {
    $page = $this->get(route('admin.announcements.index'))->assertOk()->getContent();

    expect($page)->toContain('<summary class="btn">New announcement</summary>')
        ->not->toMatch('/<details class="new-item"\s+open/');

    $this->from(route('admin.announcements.index'))->post(route('admin.announcements.store'), ['title' => '']);   // missing fields

    expect($this->get(route('admin.announcements.index'))->getContent())
        ->toMatch('/<details class="new-item"\s+open/');   // reopens so the errors can be fixed
});
