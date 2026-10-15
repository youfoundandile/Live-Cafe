{{-- resources/views/admin/announcements/edit.blade.php --}}
@extends('admin.layout')
@section('title', 'Edit announcement — Admin')

@section('admin-content')
<a href="{{ route('admin.announcements.index') }}">← Back to announcements</a>
<h1>Edit announcement</h1>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="admin-form">
    @csrf @method('PUT')
    @include('admin.announcements.fields')
    <div>
        <button type="submit">Save changes</button>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-quiet">Cancel</a>
    </div>
</form>
@endsection