@extends('layouts.app')

@section('title', 'My Orders — Live Cafe')

@section('content')
<div class="orders-page">
    <div class="orders-container">

        @if (session('success'))
            <div class="orders-flash orders-flash-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="orders-flash orders-flash-error">{{ session('error') }}</div>
        @endif

        <p class="orders-eyebrow">LIVE CAFE STORE</p>
        <h1>My orders</h1>

        @forelse($orders as $order)
            <a href="{{ route('shop.orders.show', $order) }}" class="order-row">
                <div>
                    <strong>Order #{{ $order->id }}</strong>
                    <span class="order-meta">
                        Placed {{ $order->created_at->format('d M Y, H:i') }}
                        · {{ $order->order_details_count }} {{ Str::plural('item', $order->order_details_count) }}
                    </span>
                    <span class="order-meta">
                        Collection: {{ $order->collection_time->format('D d M Y, H:i') }}
                    </span>
                </div>
                <div class="order-row-right">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <strong>R{{ number_format((float) $order->total, 2) }}</strong>
                </div>
            </a>
        @empty
            <div class="orders-empty">
                <p>You haven't placed any orders yet.</p>
                <a href="{{ route('shop.index') }}" class="orders-btn">Browse the shop</a>
            </div>
        @endforelse

        {{ $orders->links() }}

    </div>
</div>
@endsection

@push('styles')
<style>
    .orders-page { min-height: 70vh; }
    .orders-container { max-width: 900px; margin: 0 auto; padding: 1.5rem 2rem 4rem; }
    .orders-eyebrow { color: var(--accent); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; margin-bottom: 0.5rem; }
    .orders-container h1 { color: var(--green); font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3rem); margin-bottom: 1.5rem; }

    .orders-flash { padding: 0.85rem 1rem; margin-bottom: 1.5rem; border-radius: 4px; font-size: 0.9rem; }
    .orders-flash-success { background: #e6f4ea; color: var(--green); border: 1px solid var(--green); }
    .orders-flash-error { background: #fdecea; color: var(--danger); border: 1px solid var(--danger); }

    .order-row {
        display: flex; justify-content: space-between; align-items: center; gap: 1rem;
        background: var(--white); border: 1px solid var(--sage);
        padding: 1.1rem 1.25rem; margin-bottom: 0.75rem;
        text-decoration: none; color: var(--ink);
    }
    .order-row:hover { border-color: var(--green); }
    .order-meta { display: block; color: var(--slate); font-size: 0.8rem; margin-top: 0.2rem; }
    .order-row-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.4rem; }

    .status-badge { font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 3px; background: var(--sage); color: var(--green); }
    .status-cancelled { background: #fdecea; color: var(--danger); }
    .status-collected { background: #e6f4ea; color: var(--green); }

    .orders-empty { text-align: center; padding: 3rem 1rem; background: var(--white); border: 1px solid var(--sage); color: var(--slate); }
    .orders-btn { display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: var(--green); color: var(--white); text-decoration: none; border-radius: 4px; font-weight: 600; }
</style>
@endpush