{{-- resources/views/admin/events/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Events — Admin')

@section('admin-content')
<h1>Events</h1>
<a href="{{ route('admin.events.create') }}">New event</a>

<table class="data-table">
    <tr><th>Date</th><th>Time</th><th>Address</th><th>RSVPs</th><th>Expected</th><th>RSVP</th><th></th></tr>
    @forelse ($events as $event)
        @php($startsAt = $event->event_date->setTimeFromTimeString($event->event_time))
        <tr>
            <td>{{ $event->event_date->format('D d M Y') }}</td>
            <td>{{ substr($event->event_time, 0, 5) }}</td>
            <td>{{ $event->address }}</td>
            <td>{{ $event->rsvps_count }}</td>
            <td>{{ $event->rsvps_count + ($event->rsvps_sum_extras ?? 0) }}</td>
            <td>
                @if ($startsAt->isPast())
                    <span class="status-badge status-collected">Finished</span>
                @elseif (now()->lt($startsAt->subHours(8)))
                    <span class="status-badge status-confirmed">Open</span>
                @else
                    <span class="status-badge status-pending">Closed</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.events.rsvps', $event) }}">RSVP list</a>
                <a href="{{ route('admin.events.edit', $event) }}">Edit</a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline"
                      onsubmit="return confirm('Delete this event?')">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7">No events yet.</td></tr>
    @endforelse
</table>
{{ $events->links() }}
@endsection
