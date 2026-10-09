{{-- resources/views/admin/sales/index.blade.php --}}
@extends('admin.layout')
@section('title', 'POS Sales — Admin')

@section('admin-content')
<h1>POS Sales</h1>

<form method="GET" action="{{ route('admin.sales.index') }}">
    <input type="date" name="from" value="{{ $from->toDateString() }}">
    <input type="date" name="to" value="{{ $to->toDateString() }}">
    <select name="payment_method">
        <option value="">Cash and card</option>
        <option value="cash" @selected(request('payment_method') === 'cash')>Cash</option>
        <option value="card" @selected(request('payment_method') === 'card')>Card</option>
    </select>
    <select name="offline">
        <option value="">Online and offline</option>
        <option value="1" @selected(request('offline') === '1')>Offline only</option>
        <option value="0" @selected(request('offline') === '0')>Online only</option>
    </select>
    <select name="status">
        <option value="">All statuses</option>
        <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
        <option value="cancelled" @selected(request('status') === 'cancelled')>Voided</option>
    </select>
    <button type="submit">Filter</button>
</form>

<div class="stat-grid">
    @foreach ($totals as $total)
        <div class="stat-card">
            <div class="stat-card-label">{{ ucfirst($total->payment_method) }}</div>
            <div class="stat-card-value">R {{ number_format($total->revenue, 2) }}</div>
            <div class="stat-card-note">{{ $total->count }} sales</div>
        </div>
    @endforeach
</div>

<table class="data-table">
    <tr><th>#</th><th>Time</th><th>Staff</th><th>Method</th><th>Total</th><th>Status</th></tr>
    @forelse ($sales as $sale)
        <tr>
            <td><a href="{{ route('admin.sales.show', $sale) }}">{{ $sale->id }}</a></td>
            <td>
                {{ $sale->occurred_at->format('d M Y H:i') }}
                @if ($sale->is_offline)
                    <span class="status-badge status-pending">Offline</span>
                    synced {{ $sale->synced_at?->format('d M H:i') }}
                @endif
            </td>
            <td>{{ $sale->user->name }}</td>
            <td>{{ ucfirst($sale->payment_method) }}</td>
            <td>R {{ number_format($sale->total, 2) }}</td>
            <td><span class="status-badge status-{{ $sale->status }}">{{ $sale->status === 'cancelled' ? 'voided' : $sale->status }}</span></td>
        </tr>
    @empty
        <tr><td colspan="6">No sales for these dates.</td></tr>
    @endforelse
</table>
{{ $sales->links() }}
@endsection
