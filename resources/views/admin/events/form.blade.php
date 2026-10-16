{{-- resources/views/admin/events/form.blade.php --}}
@extends('admin.layout')
@section('title', ($event->exists ? 'Edit '.$event->noun() : 'New run or event').' — Admin')

@section('admin-content')
<a href="{{ route('admin.events.index') }}">← Back to runs and events</a>
<h1>{{ $event->exists ? 'Edit '.$event->noun() : 'New run or event' }}</h1>

@if ($event->exists && $event->active_rsvps_count > 0)
    <p class="admin-sub">{{ $event->active_rsvps_count }} going. Changing the date, start time or location emails them.</p>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" class="admin-form"
      action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
    @csrf
    @if ($event->exists)
        @method('PUT')
    @endif

    <label>Type
        <select name="type" required>
            <option value="run" @selected(old('type', $event->type) === 'run')>Run</option>
            <option value="event" @selected(old('type', $event->type) === 'event')>Event (no distance)</option>
        </select>
    </label>

    <label>Title
        <input type="text" name="title" value="{{ old('title', $event->title) }}" maxlength="120" placeholder="e.g. Saturday 5 km" required>
    </label>

    <label>Date
        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required>
    </label>

    <label>Start time
        <input type="time" name="event_time" value="{{ old('event_time', $event->event_time ? substr($event->event_time, 0, 5) : '') }}" required>
    </label>

    <label>Location
        <input type="text" name="address" value="{{ old('address', $event->address) }}" maxlength="255" required>
    </label>

    <fieldset>
        <legend>Runs only</legend>
        <label>Distance (km)
            <input type="number" name="distance_km" value="{{ old('distance_km', $event->distance_km) }}" step="0.1" min="0.1" max="200">
        </label>
        <label>Pace
            <input type="text" name="pace" value="{{ old('pace', $event->pace) }}" maxlength="40" placeholder="e.g. 6:00 min/km, all paces welcome">
        </label>
    </fieldset>

    <label>Dress code (optional)
        <input type="text" name="dress_code" value="{{ old('dress_code', $event->dress_code) }}" maxlength="120">
    </label>

    <label>Sponsor (optional)
        <input type="text" name="sponsor" value="{{ old('sponsor', $event->sponsor) }}" maxlength="120">
    </label>

    <label>Description (optional)
        <textarea name="description" rows="4" maxlength="5000">{{ old('description', $event->description) }}</textarea>
    </label>

    <div>
        <button type="submit">{{ $event->exists ? 'Save changes' : 'Add' }}</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-quiet">Cancel</a>
    </div>
</form>
@endsection