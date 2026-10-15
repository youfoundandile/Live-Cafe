{{-- resources/views/admin/events/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Events — Admin')

@section('admin-content')
<div class="admin-section-header">
    <h1>Runs and events</h1>
    <a href="{{ route('admin.events.create') }}" class="btn">New run or event</a>
</div>
<p class="admin-sub">A run with RSVPs can't be deleted. Cancel it instead: the RSVPs stay on record and everyone going gets an email.</p>

<table class="data-table">
    <tr><th>When</th><th>Run or event</th><th>Location</th><th>Going</th><th>Expected</th><th>Status</th><th></th></tr>
    @forelse ($events as $event)
        <tr>
            <td>{{ $event->event_date->format('D d M Y') }}, {{ $event->startTimeLabel() }}</td>
            <td>
                {{ $event->title }}<br>
                <span class="hint">{{ $event->typeLabel() }}@if ($event->distanceLabel()) · {{ $event->distanceLabel() }}@endif</span>
            </td>
            <td>{{ $event->address }}</td>
            <td>{{ $event->going_count }}</td>
            <td>{{ $event->going_count + (int) $event->going_extras }}</td>
            <td>
                @if ($event->isCancelled())
                    <span class="status-badge status-cancelled">Cancelled</span>
                @elseif ($event->hasStarted())
                    <span class="status-badge status-collected">Finished</span>
                @elseif ($event->rsvpIsOpen())
                    <span class="status-badge status-confirmed">RSVP open</span>
                @else
                    <span class="status-badge status-pending">RSVP closed</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.events.rsvps', $event) }}">RSVPs</a>
                <a href="{{ route('admin.events.edit', $event) }}">Edit</a>
                @if (! $event->isCancelled() && ! $event->hasStarted())
                    <form method="POST" action="{{ route('admin.events.cancel', $event) }}" style="display:inline"
                          onsubmit="return confirm('Cancel this {{ $event->noun() }}? Everyone going gets an email.')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-danger">Cancel</button>
                    </form>
                @endif
                @if ($event->rsvps_count === 0)
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline"
                          onsubmit="return confirm('Delete this {{ $event->noun() }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="empty">No runs or events yet.</td></tr>
    @endforelse
</table>
{{ $events->links('pagination::bootstrap-4') }}
@endsection