{{-- resources/views/admin/announcements/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Announcements — Admin')

@section('admin-content')
<h1>Announcements</h1>
<p class="admin-sub">Published announcements show on the run club and announcements pages, pinned ones first. Drafts stay private until you publish them.</p>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Folded away until "New announcement" is clicked. --}}
<details class="new-item" @if ($errors->any()) open @endif>
    <summary class="btn">New announcement</summary>

    <form method="POST" action="{{ route('admin.announcements.store') }}" class="admin-form">
        @csrf
        @include('admin.announcements.fields', ['announcement' => new \App\Models\Announcement])
        <button type="submit">Save announcement</button>
    </form>
</details>

<table class="data-table">
    <tr><th>Announcement</th><th>Status</th><th>About</th><th></th></tr>
    @forelse ($announcements as $announcement)
        <tr>
            <td>
                {{ $announcement->title }}
                @if ($announcement->is_pinned)
                    <span class="status-badge status-confirmed">Pinned</span>
                @endif
                <br><span class="hint">{{ \Illuminate\Support\Str::limit($announcement->description, 90) }}</span>
            </td>
            <td>
                @if ($announcement->isPublished())
                    <span class="status-badge status-collected">Published</span>
                    <span class="hint">{{ $announcement->published_at->format('d M Y') }}</span>
                @else
                    <span class="status-badge status-pending">Draft</span>
                @endif
            </td>
            <td>{{ $announcement->event?->title ?? '—' }}</td>
            <td>
                <a href="{{ route('admin.announcements.edit', $announcement) }}">Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" style="display:inline"
                      onsubmit="return confirm('Delete this announcement?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="4" class="empty">No announcements yet.</td></tr>
    @endforelse
</table>
{{ $announcements->links('pagination::bootstrap-4') }}
@endsection