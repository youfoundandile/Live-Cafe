<?php

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;

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

test('posts an announcement', function () {
    $this->post(route('admin.announcements.store'), ['description' => 'Saturday run moves to 6am'])
        ->assertRedirect(route('admin.announcements.index'));

    expect(Announcement::where('description', 'Saturday run moves to 6am')->exists())->toBeTrue();
});
