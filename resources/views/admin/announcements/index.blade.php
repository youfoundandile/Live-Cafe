{{-- resources/views/admin/announcements/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Announcements — Admin')

@section('admin-content')
<h1>Announcements</h1>

@error('description') <div class="alert alert-error">{{ $message }}</div> @enderror

<form method="POST" action="{{ route('admin.announcements.store') }}" class="admin-form">
    @csrf
    <label>New announcement
        <textarea name="description" rows="3" maxlength="2000" required>{{ old('description') }}</textarea>
    </label>
    <button type="submit">Post</button>
</form>

<table class="data-table">
    <tr><th>Posted</th><th>Announcement</th><th></th></tr>
    @forelse ($announcements as $announcement)
        <tr>
            <td>{{ $announcement->created_at->format('d M Y H:i') }}</td>
            <td>{{ $announcement->description }}</td>
            <td>
                <a href="{{ route('admin.announcements.edit', $announcement) }}">Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" style="display:inline"
                      onsubmit="return confirm('Delete this announcement?')">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="3">No announcements yet.</td></tr>
    @endforelse
</table>
{{ $announcements->links() }}
@endsection
