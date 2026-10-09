{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layout')
@section('title', 'Order #'.$order->id.' — Admin')

@section('admin-content')
<a href="{{ route('admin.orders.index') }}">← Back to orders</a>
<h1>Order #{{ $order->id }}</h1>

<p>Customer: {{ $order->user->name }} {{ $order->user->surname }} ({{ $order->user->email }})</p>
<p>Collection time: {{ $order->collection_time->format('d M Y H:i') }}</p>
<p>Status: {{ $order->status }}</p>

<table>
    <tr><th>Product</th><th>Quantity</th><th>Unit price</th><th>Subtotal</th></tr>
    @foreach ($order->orderDetails as $line)
        <tr>
            <td>{{ $line->product->name }}</td>
            <td>{{ $line->quantity }}</td>
            <td>R {{ number_format($line->unit_price, 2) }}</td>
            <td>R {{ number_format($line->quantity * $line->unit_price, 2) }}</td>
        </tr>
    @endforeach
    <tr><th colspan="3">Total</th><th>R {{ number_format($order->total, 2) }}</th></tr>
</table>

 @include('admin.orders.partials.status-buttons')
@endsection
