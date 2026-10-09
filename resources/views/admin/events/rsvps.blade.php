{{-- resources/views/admin/events/rsvps.blade.php --}}
@extends('admin.layout')
@section('title', 'RSVPs — Admin')

@section('admin-content')
<a href="{{ route('admin.events.index') }}">← Back to events</a>
<h1>RSVPs: {{ $event->event_date->format('D d M Y') }} at {{ substr($event->event_time, 0, 5) }}</h1>
<p>{{ $event->address }}</p>
<p>
    {{ $rsvps->count() }} members + {{ $rsvps->sum('extras') }} extras =
    <strong>{{ $rsvps->count() + $rsvps->sum('extras') }} expected</strong>
</p>

<a href="{{ route('admin.events.rsvps.export', $event) }}">Download CSV</a>

<table class="data-table">
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Extras</th><th>RSVP'd at</th></tr>
    @forelse ($rsvps as $rsvp)
        <tr>
            <td>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</td>
            <td>{{ $rsvp->user->email }}</td>
            <td>{{ $rsvp->user->phone_number }}</td>
            <td>{{ $rsvp->extras }}</td>
            <td>{{ $rsvp->created_at->format('d M Y H:i') }}</td>
        </tr>
    @empty
        <tr><td colspan="5">No RSVPs yet.</td></tr>
    @endforelse
</table>
@endsection
