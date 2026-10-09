<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventManagementController extends Controller
{
    public function index()
    {
        $events = Event::withCount('rsvps')
            ->withSum('rsvps', 'extras')
            ->orderByDesc('event_date')
            ->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        Gate::authorize('create', Event::class);

        return view('admin.events.form', ['event' => new Event]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);

        Event::create($this->validated($request));

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function show(Event $event)
    {
        return redirect()->route('admin.events.rsvps', $event);
    }

    public function edit(Event $event)
    {
        Gate::authorize('update', $event);

        return view('admin.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $event->update($this->validated($request));

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);

        // rsvps.event_id uses cascadeOnDelete, so deleting would quietly wipe members' RSVPs
        if (today()->lte($event->event_date) && $event->rsvps()->exists()) {
            return back()->with('error', "Members have RSVP'd, so contact them first.");
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function rsvps(Event $event)
    {
        $rsvps = Rsvp::with('user')->where('event_id', $event->id)->orderBy('created_at')->get();

        return view('admin.events.rsvps', compact('event', 'rsvps'));
    }

    public function exportRsvps(Event $event)
    {
        $rsvps = Rsvp::with('user')->where('event_id', $event->id)->orderBy('created_at')->get();

        return response()->streamDownload(function () use ($rsvps) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Surname', 'Email', 'Phone', 'Extras', 'RSVP time'], escape: '');
            foreach ($rsvps as $rsvp) {
                /** @var User $user */
                $user = $rsvp->user;
                fputcsv($out, [$user->name, $user->surname, $user->email, $user->phone_number, $rsvp->extras, $rsvp->created_at], escape: '');
            }
            fclose($out);
        }, "event-{$event->id}-rsvps.csv", ['Content-Type' => 'text/csv']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
    }
}
