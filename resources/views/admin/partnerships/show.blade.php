{{-- resources/views/admin/partnerships/show.blade.php --}}
@extends('admin.layout')
@section('title', $partnership->name . ' — Admin')

@section('admin-content')
<a href="{{ route('admin.partnerships.index') }}">← Back to partnerships</a>
<h1>{{ $partnership->name }}</h1>
<p>{{ $partnership->description }}</p>

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="admin-section">
    <div class="admin-section-header"><h2>Add a member</h2></div>
    <p class="admin-sub">They need an account first. Adding someone who was deactivated switches them back on.</p>
    <form method="POST" action="{{ route('admin.partnerships.members.store', $partnership) }}" class="admin-form">
        @csrf
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>Employee ID
            <input type="text" name="employee_id" value="{{ old('employee_id') }}" maxlength="100" required>
        </label>
        <button type="submit">Add member</button>
    </form>
</section>

<section class="admin-section">
    <div class="admin-section-header"><h2>Members</h2></div>
    <table class="data-table">
        <tr><th>Name</th><th>Email</th><th>Employee ID</th><th>Status</th><th></th></tr>
        @forelse ($partnership->partnershipMembers as $member)
            <tr>
                <td>{{ $member->user->name }} {{ $member->user->surname }}</td>
                <td>{{ $member->user->email }}</td>
                <td>{{ $member->employee_id }}</td>
                <td>
                    <span class="status-badge {{ $member->status ? 'status-confirmed' : 'status-cancelled' }}">
                        {{ $member->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    @if ($member->status)
                        <form method="POST" action="{{ route('admin.partnerships.members.destroy', [$partnership, $member]) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit">Deactivate</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No members yet.</td></tr>
        @endforelse
    </table>
</section>
@endsection
