{{-- resources/views/admin/events/form.blade.php --}}
@extends('admin.layout')
@section('title', ($event->exists ? 'Edit event' : 'New event') . ' — Admin')

@section('admin-content')
<h1>{{ $event->exists ? 'Edit event' : 'New event' }}</h1>

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

    <label>Date
        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required>
    </label>

    <label>Start time
        <input type="time" name="event_time" value="{{ old('event_time', $event->event_time ? substr($event->event_time, 0, 5) : '') }}" required>
    </label>

    <label>Address
        <input type="text" name="address" value="{{ old('address', $event->address) }}" maxlength="255" required>
    </label>

    <label>Description
        <textarea name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
    </label>

    <button type="submit">{{ $event->exists ? 'Save changes' : 'Create event' }}</button>
    <a href="{{ route('admin.events.index') }}">Cancel</a>
</form>
@endsection
