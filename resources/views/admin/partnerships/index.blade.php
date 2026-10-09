{{-- resources/views/admin/partnerships/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Partnerships — Admin')

@section('admin-content')
<h1>Partnerships</h1>
<a href="{{ route('admin.partnerships.create') }}">New partnership</a>

<table class="data-table">
    <tr><th>Name</th><th>Active members</th><th>Status</th><th></th></tr>
    @forelse ($partnerships as $partnership)
        <tr>
            <td><a href="{{ route('admin.partnerships.show', $partnership) }}">{{ $partnership->name }}</a></td>
            <td>{{ $partnership->active_members_count }}</td>
            <td>
                <span class="status-badge {{ $partnership->status ? 'status-confirmed' : 'status-cancelled' }}">
                    {{ $partnership->status ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.partnerships.edit', $partnership) }}">Edit</a>
                @if ($partnership->status)
                    <form method="POST" action="{{ route('admin.partnerships.destroy', $partnership) }}" style="display:inline"
                          onsubmit="return confirm('Deactivate this partnership?')">
                        @csrf @method('DELETE')
                        <button type="submit">Deactivate</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="4">No partnerships yet.</td></tr>
    @endforelse
</table>
@endsection
