<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Running\EventRequest;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use App\Services\RunningClub\RunningClubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

// Runs and club events. The form rules (EventRequest) and the emails to members
// (RunningClubService) are the run club's own, so this page and /running/manage behave the same.
class EventManagementController extends Controller
{
    public function __construct(private readonly RunningClubService $runningClub) {}

    public function index(): View
    {
        $events = Event::withCount(['rsvps', 'rsvps as going_count' => fn ($q) => $q->active()])
            ->withSum(['rsvps as going_extras' => fn ($q) => $q->active()], 'extras')
            ->orderByDesc('event_date')
            ->orderByDesc('event_time')
            ->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        Gate::authorize('create', Event::class);

        return view('admin.events.form', ['event' => new Event]);
    }

    public function store(EventRequest $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);

        $event = Event::create($request->eventAttributes());

        return redirect()->route('admin.events.index')->with('success', "Added {$event->title}.");
    }

    public function show(Event $event): RedirectResponse
    {
        return redirect()->route('admin.events.rsvps', $event);
    }

    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        $event->loadCount('activeRsvps');   // the form warns that changing the date, time or place emails them

        return view('admin.events.form', compact('event'));
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $this->runningClub->updateEvent($event, $request->eventAttributes());

        return redirect()->route('admin.events.index')->with('success', "Saved {$event->title}.");
    }

    // Cancelling keeps the RSVPs on record and emails everyone who was going
    public function cancel(Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $this->runningClub->cancelEvent($event);

        return redirect()->route('admin.events.index')->with('success', "Cancelled {$event->title}. Everyone who was going has been emailed.");
    }

    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        // rsvps.event_id uses cascadeOnDelete, so deleting would quietly wipe members' RSVPs
        if ($event->rsvps()->exists()) {
            return back()->with('error', "{$event->title} has RSVPs, so cancel it instead. That keeps the RSVP records.");
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', "Deleted {$event->title}.");
    }

    public function rsvps(Event $event): View
    {
        $rsvps = Rsvp::with('user')->where('event_id', $event->id)->oldest()->get();

        $going = $rsvps->filter(fn (Rsvp $rsvp) => $rsvp->isGoing())->values();
        $cancelled = $rsvps->reject(fn (Rsvp $rsvp) => $rsvp->isGoing())->values();

        return view('admin.events.rsvps', compact('event', 'going', 'cancelled'));
    }

    public function exportRsvps(Event $event): StreamedResponse
    {
        $rsvps = Rsvp::with('user')->where('event_id', $event->id)->oldest()->get();

        return response()->streamDownload(function () use ($rsvps) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Surname', 'Email', 'Phone', 'Extras', 'Status', 'RSVP time'], escape: '');
            foreach ($rsvps as $rsvp) {
                /** @var User $user */
                $user = $rsvp->user;
                fputcsv($out, [$user->name, $user->surname, $user->email, $user->phone_number, $rsvp->extras, $rsvp->isGoing() ? 'Going' : 'Cancelled', $rsvp->created_at], escape: '');
            }
            fclose($out);
        }, "event-{$event->id}-rsvps.csv", ['Content-Type' => 'text/csv']);
    }
}
