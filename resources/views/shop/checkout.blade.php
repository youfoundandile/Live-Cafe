@extends('layouts.app')

@section('title', 'Checkout — Live Cafe')

@section('content')
<div class="checkout-page">
    <div class="checkout-container">

        <div class="checkout-header">
            <p class="checkout-eyebrow">LIVE CAFE STORE</p>
            <h1>Checkout</h1>
            <p class="checkout-subtitle">Confirm your order and choose a collection time.</p>
        </div>

        @if (session('error'))
            <div class="checkout-flash checkout-flash-error">{{ session('error') }}</div>
        @endif

        <div class="checkout-layout">

            {{-- Main: collection details form --}}
            <section class="checkout-form-panel">
                <h2>Collection details</h2>

                <form action="{{ route('shop.checkout.store') }}" method="POST" id="checkout-form">
                    @csrf

                    <label for="collection_time">Pickup date &amp; time</label>
                    <input
                        type="datetime-local"
                        id="collection_time"
                        name="collection_time"
                        value="{{ old('collection_time') }}"
                        required
                    >
                    @error('collection_time')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <p class="checkout-note">
                        Orders are collection only from Live Cafe, Sandton. No delivery is available.
                    </p>
                </form>
            </section>

            {{-- Sidebar: order summary --}}
            <aside class="checkout-summary">
                <h2>Order summary</h2>

                <ul class="summary-items">
                    @foreach($products as $item)
                        <li class="summary-item">
                            <div class="summary-item-info">
                                <span class="summary-item-name">{{ $item['product']->name }}</span>
                                <span class="summary-item-qty">× {{ $item['quantity'] }}</span>
                            </div>
                            <span class="summary-item-price">R{{ number_format((float) $item['lineTotal'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="summary-divider"></div>

                <div class="summary-row summary-total">
                    <span>Total</span>
                    <strong>R{{ number_format((float) $total, 2) }}</strong>
                </div>

                <button type="submit" form="checkout-form" class="place-order-btn">
                    Place order
                </button>

                <a href="{{ route('shop.cart.index') }}" class="back-to-cart">← Back to cart</a>
            </aside>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .checkout-page { min-height: 70vh; }
    .checkout-container { max-width: 1100px; margin: 0 auto; padding: 1.5rem 2rem 4rem; }

    .checkout-header { margin-bottom: 2rem; }
    .checkout-eyebrow {
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        margin-bottom: 0.5rem;
    }
    .checkout-header h1 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: clamp(2rem, 5vw, 3rem);
        line-height: 1.1;
        margin-bottom: 0.5rem;
    }
    .checkout-subtitle { color: var(--slate); font-size: 0.95rem; }

    .checkout-flash {
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .checkout-flash-error { background: #fdecea; color: var(--danger); border: 1px solid var(--danger); }

    .checkout-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 2rem;
        align-items: start;
    }

    /* Form panel */
    .checkout-form-panel {
        background: var(--white);
        border: 1px solid var(--sage);
        padding: 1.75rem;
    }
    .checkout-form-panel h2 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: 1.4rem;
        margin-bottom: 1.25rem;
    }
    .checkout-form-panel label {
        display: block;
        color: var(--slate);
        font-size: 0.8rem;
        margin-bottom: 0.4rem;
    }
    .checkout-form-panel input[type="datetime-local"] {
        width: 100%;
        padding: 0.65rem;
        border: 1px solid var(--sage);
        background: var(--base);
        font-family: var(--font-body);
        margin-bottom: 0.4rem;
    }
    .field-error { color: var(--danger); font-size: 0.8rem; margin-bottom: 1rem; }
    .checkout-note {
        color: var(--slate);
        font-size: 0.8rem;
        line-height: 1.5;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--sage);
    }

    /* Summary sidebar */
    .checkout-summary {
        position: sticky;
        top: calc(var(--nav-h) + 1rem);
        background: var(--white);
        border: 1px solid var(--sage);
        padding: 1.5rem;
    }
    .checkout-summary h2 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: 1.4rem;
        margin-bottom: 1.25rem;
    }

    .summary-items {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 0.75rem;
        font-size: 0.875rem;
    }
    .summary-item-info { display: flex; gap: 0.4rem; color: var(--ink); }
    .summary-item-qty { color: var(--slate); }
    .summary-item-price { color: var(--ink); white-space: nowrap; }

    .summary-divider { border-top: 1px solid var(--sage); margin: 1rem 0; }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .summary-total { color: var(--ink); font-size: 1.05rem; margin-bottom: 1.5rem; }
    .summary-total strong { color: var(--green); font-size: 1.35rem; }

    .place-order-btn {
        display: block;
        width: 100%;
        padding: 0.85rem 1rem;
        background: var(--green);
        color: var(--white);
        border: none;
        text-align: center;
        font-family: var(--font-body);
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .place-order-btn:hover { opacity: 0.9; }

    .back-to-cart {
        display: block;
        margin-top: 1rem;
        color: var(--green);
        text-align: center;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .back-to-cart:hover { text-decoration: underline; }

    @media (max-width: 900px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .checkout-summary { position: static; }
    }
</style>
@endpush