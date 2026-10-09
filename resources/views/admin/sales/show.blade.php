{{-- resources/views/admin/sales/show.blade.php --}}
@extends('admin.layout')
@section('title', 'Sale #'.$sale->id.' — Admin')

@section('admin-content')
<a href="{{ route('admin.sales.index') }}">← Back to sales</a>
<h1>Sale #{{ $sale->id }}</h1>

<p>Staff: {{ $sale->user->name }} {{ $sale->user->surname }}</p>
<p>
    Rung up: {{ $sale->occurred_at->format('d M Y H:i') }} · {{ ucfirst($sale->payment_method) }}
    @if ($sale->is_offline)
        · <span class="status-badge status-pending">Offline</span> synced {{ $sale->synced_at?->format('d M Y H:i') }}
    @endif
</p>

<table class="data-table">
    <tr><th>Product</th><th>Quantity</th><th>Unit price</th><th>Subtotal</th></tr>
    @foreach ($lines as $line)
        <tr>
            <td>{{ $line->product->name }}</td>
            <td>{{ $line->quantity }}</td>
            <td>R {{ number_format($line->unit_price, 2) }}</td>
            <td>R {{ number_format($line->quantity * $line->unit_price, 2) }}</td>
        </tr>
    @endforeach
    <tr><th colspan="3">Total</th><th>R {{ number_format($sale->total, 2) }}</th></tr>
</table>

@if ($sale->status === 'cancelled')
    <section class="admin-section">
        <h2>Voided</h2>
        <p>By {{ $voidedBy?->name ?? 'unknown' }} on {{ $sale->voided_at?->format('d M Y H:i') }}</p>
        <p>Reason: {{ $sale->void_reason }}</p>
    </section>
@else
    <section class="admin-section">
        <h2>Void this sale</h2>
        @error('reason') <div class="alert alert-error">{{ $message }}</div> @enderror
        <form method="POST" action="{{ route('admin.sales.void', $sale) }}">
            @csrf
            <textarea name="reason" rows="3" required minlength="5" maxlength="255" placeholder="Why is this sale being voided?">{{ old('reason') }}</textarea>
            <button type="submit">Void sale and return stock</button>
        </form>
    </section>
@endif
@endsection
