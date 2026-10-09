{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Orders — Admin')

@section('admin-content')
<h1>Orders</h1>

<form method="GET" action="{{ route('admin.orders.index') }}">
    <select name="status">
        <option value="">All</option>
        @foreach (array_keys(\App\Models\Order::TRANSITIONS) as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}">
    <button type="submit">Filter</button>
</form>

<table>
    <tr><th>#</th><th>Customer</th><th>Collection time</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr>
    @foreach ($orders as $order)
        <tr>
            <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->id }}</a></td>
            <td>{{ $order->user->name }} {{ $order->user->surname }}</td>
            <td>{{ $order->collection_time->format('d M Y H:i') }}</td>
            <td>{{ $order->order_details_count }}</td>
            <td>R {{ number_format($order->total, 2) }}</td>
            <td>{{ $order->status }}</td>
            <td>
                 @include('admin.orders.partials.status-buttons') 
            </td>
        </tr>
    @endforeach
</table>
{{ $orders->links() }}
@endsection
