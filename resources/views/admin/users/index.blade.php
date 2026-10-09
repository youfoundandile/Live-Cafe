{{-- resources/views/admin/users/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Users — Admin')

@section('admin-content')
<h1>Users</h1>

<form method="GET" action="{{ route('admin.users.index') }}">
    <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, surname or email">
    <select name="role">
        <option value="">All roles</option>
        @foreach (['customer', 'staff', 'admin'] as $role)
            <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
</form>

<table class="data-table">
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th></th></tr>
    @forelse ($users as $user)
        <tr>
            <td>{{ $user->name }} {{ $user->surname }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                @unless ($user->is(auth()->user()))
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline"
                          onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endunless
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No users match.</td></tr>
    @endforelse
</table>
{{ $users->links() }}
@endsection
