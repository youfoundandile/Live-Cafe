{{-- resources/views/admin/users/create.blade.php --}}
@extends('admin.layout')
@section('title', 'Add staff member — Admin')

@section('admin-content')
<a href="{{ route('admin.users.index') }}">← Back to users</a>
<h1>Add staff member</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
    @csrf

    <label>Name
        <input type="text" name="name" value="{{ old('name') }}" maxlength="100" required>
    </label>

    <label>Surname
        <input type="text" name="surname" value="{{ old('surname') }}" maxlength="100" required>
    </label>

    <label>Email
        <input type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="off" required>
    </label>

    <label>Phone
        <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="20" required>
    </label>

    <label>Role
        <select name="role" required>
            @foreach (['staff', 'admin'] as $role)
                <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
    </label>

    <label>Temporary password
        <input type="password" name="password" autocomplete="new-password" required>
    </label>

    <label>Confirm password
        <input type="password" name="password_confirmation" autocomplete="new-password" required>
    </label>

    <button type="submit" class="btn">Add staff member</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-quiet">Cancel</a>
</form>
@endsection
