@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' — Live Cafe')

@section('content')
<div class="orders-page">
    <div class="orders-container">

        <a href="{{ route('shop.orders.index') }}" class="back-link">← All orders</a>

        @if (session('success'))
            <div class="orders-flash orders-flash-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="orders-flash orders-flash-error">{{ session('error') }}</div>
        @endif

        <h1>Order #{{ $order->id }}</h1>

        <div class="order-panel">
            <div class="order-panel-row">
                <span>Status</span>
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="order-panel-row">
                <span>Placed</span>
                <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
            </div>
            <div class="order-panel-row">
                <span>Collection time</span>
                <strong>{{ $order->collection_time->format('D d M Y, H:i') }}</strong>
            </div>
        </div>

        <div class="order-panel">
            @foreach($order->orderDetails as $detail)
                <div class="order-panel-row">
                    <span>{{ $detail->product->name }} × {{ $detail->quantity }}</span>
                    <strong>R{{ number_format((float) $detail->unit_price * $detail->quantity, 2) }}</strong>
                </div>
            @endforeach
            <div class="order-panel-row order-total">
                <span>Total</span>
                <strong>R{{ number_format((float) $order->total, 2) }}</strong>
            </div>
        </div>

        <p class="orders-note">Collection only from Live Cafe, Sandton.</p>

        @if(in_array($order->status, ['pending', 'confirmed']))
            <form action="{{ route('shop.orders.cancel', $order) }}" method="POST"
                  onsubmit="return confirm('Cancel this order?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="cancel-order-btn">Cancel order</button>
            </form>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .orders-page { min-height: 70vh; }
    .orders-container { max-width: 900px; margin: 0 auto; padding: 1.5rem 2rem 4rem; }
    .orders-container h1 { color: var(--green); font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3rem); margin-bottom: 1.5rem; }

    .orders-flash { padding: 0.85rem 1rem; margin-bottom: 1.5rem; border-radius: 4px; font-size: 0.9rem; }
    .orders-flash-success { background: #e6f4ea; color: var(--green); border: 1px solid var(--green); }
    .orders-flash-error { background: #fdecea; color: var(--danger); border: 1px solid var(--danger); }

    .status-badge { font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 3px; background: var(--sage); color: var(--green); }
    .status-cancelled { background: #fdecea; color: var(--danger); }
    .status-collected { background: #e6f4ea; color: var(--green); }

    .back-link { display: inline-block; font-size: 0.875rem; color: var(--slate); text-decoration: none; margin-bottom: 1rem; }
    .back-link:hover { color: var(--green); }
    .order-panel { background: var(--white); border: 1px solid var(--sage); padding: 0.5rem 1.25rem; margin-bottom: 1rem; }
    .order-panel-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--sage); font-size: 0.9rem; }
    .order-panel-row:last-child { border-bottom: none; }
    .order-total { font-size: 1.05rem; }
    .order-total strong { color: var(--green); font-size: 1.2rem; }
    .orders-note { color: var(--slate); font-size: 0.8rem; margin-bottom: 1.5rem; }
    .cancel-order-btn { background: transparent; border: 1px solid var(--danger); color: var(--danger); padding: 0.65rem 1.25rem; border-radius: 4px; cursor: pointer; font-family: var(--font-body); font-weight: 600; }
    .cancel-order-btn:hover { background: var(--danger); color: var(--white); }
</style>
@endpush