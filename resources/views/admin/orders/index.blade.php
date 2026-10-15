{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Orders — Admin')

@section('admin-content')
<h1>Orders</h1>
<p class="admin-sub">Online orders from the shop. Cancelling an order puts its stock back.</p>

<form method="GET" action="{{ route('admin.orders.index') }}" class="filter-bar">
    <label>Status
        <select name="status">
            <option value="">All</option>
            @foreach (array_keys(\App\Models\Order::TRANSITIONS) as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </label>
    <label>Collection date
        <input type="date" name="date" value="{{ request('date') }}">
    </label>
    <button type="submit">Filter</button>
    @if (request()->anyFilled(['status', 'date']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-quiet">Clear</a>
    @endif
</form>

<table class="data-table">
    <tr><th>#</th><th>Customer</th><th>Collection time</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr>
    @forelse ($orders as $order)
        <tr>
            <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->id }}</a></td>
            <td>{{ $order->user->name }} {{ $order->user->surname }}</td>
            <td>{{ $order->collection_time->format('d M Y H:i') }}</td>
            <td>{{ $order->order_details_count }}</td>
            <td>R {{ number_format($order->total, 2) }}</td>
            <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
            <td>@include('admin.orders.status-buttons')</td>
        </tr>
    @empty
        <tr><td colspan="7" class="empty">No orders match these filters.</td></tr>
    @endforelse
</table>
{{ $orders->links('pagination::bootstrap-4') }}
@endsection