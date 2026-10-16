{{-- resources/views/admin/events/rsvps.blade.php --}}
@extends('admin.layout')
@section('title', 'RSVPs — Admin')

@section('admin-content')
<a href="{{ route('admin.events.index') }}">← Back to runs and events</a>
<h1>RSVPs: {{ $event->title }}</h1>
<p class="admin-sub">
    {{ $event->event_date->format('D d M Y') }} at {{ $event->startTimeLabel() }} · {{ $event->address }}
    @if ($event->isCancelled())
        <span class="status-badge status-cancelled">Cancelled</span>
    @endif
</p>

<div class="admin-section-header">
    <h2>{{ $going->count() }} going + {{ $going->sum('extras') }} {{ $event->extrasNoun() }} = {{ $going->sum(fn ($rsvp) => $rsvp->headcount()) }} expected</h2>
    <a href="{{ route('admin.events.rsvps.export', $event) }}" class="btn btn-quiet">Download CSV</a>
</div>

<table class="data-table">
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>{{ ucfirst($event->extrasNoun()) }}</th><th>RSVP'd at</th></tr>
    @forelse ($going as $rsvp)
        <tr>
            <td>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</td>
            <td>{{ $rsvp->user->email }}</td>
            <td>{{ $rsvp->user->phone_number }}</td>
            <td>{{ $rsvp->extras }}</td>
            <td>{{ $rsvp->created_at->format('d M Y H:i') }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="empty">Nobody is going yet.</td></tr>
    @endforelse
</table>

@if ($cancelled->isNotEmpty())
    <section class="admin-section">
        <div class="admin-section-header"><h2>Cancelled their RSVP ({{ $cancelled->count() }})</h2></div>
        <table class="data-table">
            <tr><th>Name</th><th>Email</th><th>Cancelled at</th></tr>
            @foreach ($cancelled as $rsvp)
                <tr>
                    <td>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</td>
                    <td>{{ $rsvp->user->email }}</td>
                    <td>{{ $rsvp->cancelled_at?->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </table>
    </section>
@endif
@endsection