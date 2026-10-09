{{-- resources/views/admin/users/edit.blade.php --}}
@extends('admin.layout')
@section('title', 'Edit user — Admin')

@section('admin-content')
<a href="{{ route('admin.users.index') }}">← Back to users</a>
<h1>Edit {{ $user->name }} {{ $user->surname }}</h1>
<p>{{ $user->email }}</p>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
    @csrf @method('PUT')

    <label>Name
        <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required>
    </label>

    <label>Surname
        <input type="text" name="surname" value="{{ old('surname', $user->surname) }}" maxlength="100" required>
    </label>

    <label>Phone
        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" maxlength="20" required>
    </label>

    <label>Role
        <select name="role" required>
            @foreach (['customer', 'staff', 'admin'] as $role)
                <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
    </label>

    <button type="submit">Save changes</button>
    <a href="{{ route('admin.users.index') }}">Cancel</a>
</form>
@endsection
