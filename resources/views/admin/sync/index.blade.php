{{-- resources/views/admin/sync/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Sync conflicts — Admin')

@section('admin-content')
<h1>Sync conflicts</h1>
<p class="admin-sub">Offline sales that sold more than we had in stock. Explain each one.</p>

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
    <div class="admin-section-header"><h2>Open</h2></div>
    <table class="data-table">
        <tr><th>Sale</th><th>Till staff</th><th>Rung up</th><th>Item</th><th>Short by</th><th>Resolve</th></tr>
        @forelse ($open as $conflict)
            <tr>
                    <form method="POST" action="{{ route('admin.sync.resolve', $conflict) }}">
                        @csrf @method('PATCH')
                        <select name="resolution">
                            <option value="recount">Recount</option>
                            <option value="voided">Sale voided</option>
                            <option value="accepted_loss">Accept loss</option>
                        </select>
                        <input type="number" name="actual_count" step="0.001" min="0" placeholder="Real count (recount only)">
                        <input type="text" name="note" required maxlength="255" placeholder="What happened?">
                        <button type="submit">Resolve</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No open conflicts.</td></tr>
        @endforelse
    </table>
</section>

<section class="admin-section">
    <div class="admin-section-header"><h2>Recently resolved</h2></div>
    <table class="data-table">
        <tr><th>Sale</th><th>Item</th><th>Short by</th><th>Resolution</th><th>Note</th><th>By</th><th>When</th></tr>
        @forelse ($recent as $conflict)
            <tr>
                <td>#{{ $conflict->sales_id }}</td>
                <td>{{ $conflict->stockable->name }}</td>
                <td>{{ (float) $conflict->shortfall }}</td>
                <td>{{ str_replace('_', ' ', $conflict->resolution) }}</td>
                <td>{{ $conflict->resolution_note }}</td>
                <td>{{ $conflict->resolver?->name }}</td>
                <td>{{ $conflict->resolved_at->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="7">Nothing resolved yet.</td></tr>
        @endforelse
    </table>
</section>
@endsection
