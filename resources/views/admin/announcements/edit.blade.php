{{-- resources/views/admin/announcements/edit.blade.php --}}
@extends('admin.layout')
@section('title', 'Edit announcement — Admin')

@section('admin-content')
<h1>Edit announcement</h1>

@error('description') <div class="alert alert-error">{{ $message }}</div> @enderror

<form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="admin-form">
    @csrf @method('PUT')
    <label>Announcement
        <textarea name="description" rows="4" maxlength="2000" required>{{ old('description', $announcement->description) }}</textarea>
    </label>
    <button type="submit">Save changes</button>
    <a href="{{ route('admin.announcements.index') }}">Cancel</a>
</form>
@endsection
