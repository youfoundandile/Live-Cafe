{{-- resources/views/admin/partnerships/form.blade.php --}}
@extends('admin.layout')
@section('title', ($partnership->exists ? 'Edit partnership' : 'New partnership') . ' — Admin')

@section('admin-content')
<h1>{{ $partnership->exists ? 'Edit ' . $partnership->name : 'New partnership' }}</h1>

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
      action="{{ $partnership->exists ? route('admin.partnerships.update', $partnership) : route('admin.partnerships.store') }}">
    @csrf
    @if ($partnership->exists)
        @method('PUT')
    @endif

    <label>Name
        <input type="text" name="name" value="{{ old('name', $partnership->name) }}" maxlength="150" required>
    </label>

    <label>Description
        <textarea name="description" rows="3">{{ old('description', $partnership->description) }}</textarea>
    </label>

    <input type="hidden" name="status" value="0">
    <label class="checkbox">
        <input type="checkbox" name="status" value="1" @checked(old('status', $partnership->status))>
        Active
    </label>

    <button type="submit">{{ $partnership->exists ? 'Save changes' : 'Create partnership' }}</button>
    <a href="{{ route('admin.partnerships.index') }}">Cancel</a>
</form>
@endsection
