@extends('layouts.app')

@section('title', 'Cart — Live Cafe')

@section('content')
<div class="cart-page">
    <div class="cart-container">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="cart-flash cart-flash-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="cart-flash cart-flash-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Page heading --}}
        <div class="cart-header">
            <div>
                <p class="cart-eyebrow">LIVE CAFE STORE</p>
                <h1>Your Cart</h1>
                <p class="cart-subtitle">
                    Review your items before continuing to checkout.
                </p>
            </div>

            @if(count($products) > 0)
                <form action="{{ route('shop.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="clear-cart-btn">
                        Clear cart
                    </button>
                </form>
            @endif
        </div>

        @if(count($products) > 0)

            <div class="cart-layout">

                {{-- Cart items --}}
                <section class="cart-items">

                    @foreach($products as $item)
                        <article class="cart-item">

                            {{-- Product image --}}
                            <div class="cart-item-image">
                                @php
                                    $image = $item['product']->images
                                        ->sortBy('sort_order')
                                        ->first();
                                @endphp

                                @if($image)
                                    <img
                                        src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $image->alt_text ?? $item['product']->name }}"
                                    >
                                @else
                                    <div class="cart-item-placeholder">
                                        Live Cafe
                                    </div>
                                @endif
                            </div>

                            {{-- Product information --}}
                            <div class="cart-item-info">
                                <div>
                                    <h2>{{ $item['product']->name }}</h2>

                                    @if($item['product']->category)
                                        <p class="cart-item-category">
                                            {{ $item['product']->category->name }}
                                        </p>
                                    @endif
                                </div>

                                <p class="cart-item-price">
                                    R{{ number_format((float) $item['product']->price, 2) }}
                                    each
                                </p>
                            </div>

                            {{-- Quantity and remove --}}
                            <div class="cart-item-actions">

                                <form
                                    action="{{ route('shop.cart.update', $item['product']) }}"
                                    method="POST"
                                    class="quantity-form"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <label for="quantity-{{ $item['product']->id }}">
                                        Quantity
                                    </label>

                                    <div class="quantity-controls">
                                        <input
                                            id="quantity-{{ $item['product']->id }}"
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="{{ $item['product']->quantity }}"
                                        >

                                        <button type="submit">
                                            Update
                                        </button>
                                    </div>
                                </form>

                                <form
                                    action="{{ route('shop.cart.remove', $item['product']) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="remove-btn">
                                        Remove
                                    </button>
                                </form>

                            </div>

                            {{-- Line total --}}
                            <div class="cart-item-total">
                                <span>Item total</span>

                                <strong>
                                    R{{ number_format((float) $item['lineTotal'], 2) }}
                                </strong>
                            </div>

                        </article>
                    @endforeach

                </section>

                {{-- Order summary --}}
                <aside class="cart-summary">

                    <h2>Order Summary</h2>

                    <div class="summary-row">
                        <span>Subtotal</span>

                        <strong>
                            R{{ number_format((float) $total, 2) }}
                        </strong>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row summary-total">
                        <span>Total</span>

                        <strong>
                            R{{ number_format((float) $total, 2) }}
                        </strong>
                    </div>

                    <p class="collection-note">
                        Collection details will be confirmed during checkout.
                    </p>

                    <a
                        href="{{ route('shop.checkout.index') }}"
                        class="checkout-btn"
                    >
                        Continue to Checkout
                    </a>

                    <a
                        href="{{ route('shop.index') }}"
                        class="continue-shopping"
                    >
                        ← Continue shopping
                    </a>

                </aside>

            </div>

        @else

            {{-- Empty cart --}}
            <section class="empty-cart">

                <div class="empty-cart-icon">
                    🛒
                </div>

                <h2>Your cart is empty</h2>

                <p>
                    You haven't added anything to your cart yet.
                    Browse the Live Cafe store to find something you like.
                </p>

                <a
                    href="{{ route('shop.index') }}"
                    class="checkout-btn empty-cart-btn"
                >
                    Browse the Shop
                </a>

            </section>

        @endif

    </div>
</div>
@endsection


{{-- Cart-specific styling --}}
@push('styles')
<style>
    .cart-page {
        min-height: 70vh;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem 2rem 4rem;
    }

    /* Flash messages */
    .cart-flash {
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .cart-flash-success {
        background: #e6f4ea;
        color: var(--green);
        border: 1px solid var(--green);
    }

    .cart-flash-error {
        background: #fdecea;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    /* Header */
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .cart-eyebrow {
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        margin-bottom: 0.5rem;
    }

    .cart-header h1 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: clamp(2rem, 5vw, 3rem);
        line-height: 1.1;
        margin-bottom: 0.5rem;
    }

    .cart-subtitle {
        color: var(--slate);
        font-size: 0.95rem;
    }

    .clear-cart-btn {
        background: transparent;
        border: 1px solid var(--danger);
        color: var(--danger);
        padding: 0.65rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-family: var(--font-body);
        font-weight: 600;
    }

    .clear-cart-btn:hover {
        background: var(--danger);
        color: var(--white);
    }

    /* Main layout */
    .cart-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 2rem;
        align-items: start;
    }

    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Individual item */
    .cart-item {
        display: grid;
        grid-template-columns: 120px minmax(0, 1fr) auto;
        gap: 1.25rem;
        align-items: center;
        background: var(--white);
        border: 1px solid var(--sage);
        padding: 1rem;
    }

    .cart-item-image {
        width: 120px;
        height: 120px;
        overflow: hidden;
        background: var(--sage);
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--green);
        font-family: var(--font-display);
        font-size: 0.85rem;
        text-align: center;
        padding: 1rem;
    }

    .cart-item-info {
        align-self: stretch;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1rem;
    }

    .cart-item-info h2 {
        color: var(--ink);
        font-family: var(--font-display);
        font-size: 1.25rem;
        margin-bottom: 0.3rem;
    }

    .cart-item-category {
        color: var(--slate);
        font-size: 0.8rem;
    }

    .cart-item-price {
        color: var(--slate);
        font-size: 0.9rem;
    }

    /* Quantity controls */
    .cart-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.8rem;
    }

    .quantity-form label {
        display: block;
        color: var(--slate);
        font-size: 0.75rem;
        margin-bottom: 0.35rem;
        text-align: right;
    }

    .quantity-controls {
        display: flex;
        gap: 0.4rem;
    }

    .quantity-controls input {
        width: 70px;
        padding: 0.55rem;
        border: 1px solid var(--sage);
        background: var(--base);
        font-family: var(--font-body);
        text-align: center;
    }

    .quantity-controls button {
        border: none;
        background: var(--accent);
        color: var(--white);
        padding: 0.55rem 0.8rem;
        cursor: pointer;
        font-family: var(--font-body);
        font-weight: 600;
        border-radius: 3px;
    }

    .quantity-controls button:hover {
        opacity: 0.88;
    }

    .remove-btn {
        border: none;
        background: transparent;
        color: var(--danger);
        cursor: pointer;
        font-family: var(--font-body);
        font-size: 0.8rem;
    }

    .remove-btn:hover {
        text-decoration: underline;
    }

    /* Item total */
    .cart-item-total {
        grid-column: 2 / -1;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--sage);
    }

    .cart-item-total span {
        color: var(--slate);
        font-size: 0.8rem;
    }

    .cart-item-total strong {
        color: var(--green);
        font-size: 1.05rem;
    }

    /* Order summary */
    .cart-summary {
        position: sticky;
        top: calc(var(--nav-h) + 1rem);
        background: var(--white);
        border: 1px solid var(--sage);
        padding: 1.5rem;
    }

    .cart-summary h2 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        color: var(--slate);
        font-size: 0.9rem;
    }

    .summary-row strong {
        color: var(--ink);
    }

    .summary-divider {
        border-top: 1px solid var(--sage);
        margin: 1.25rem 0;
    }

    .summary-total {
        color: var(--ink);
        font-size: 1.05rem;
    }

    .summary-total strong {
        color: var(--green);
        font-size: 1.35rem;
    }

    .collection-note {
        color: var(--slate);
        font-size: 0.78rem;
        line-height: 1.5;
        margin: 1rem 0 1.25rem;
    }

    .checkout-btn {
        display: block;
        width: 100%;
        padding: 0.85rem 1rem;
        background: var(--green);
        color: var(--white);
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        border-radius: 4px;
        transition: opacity 0.15s;
    }

    .checkout-btn:hover {
        opacity: 0.9;
    }

    .continue-shopping {
        display: block;
        margin-top: 1rem;
        color: var(--green);
        text-align: center;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .continue-shopping:hover {
        text-decoration: underline;
    }

    /* Empty cart */
    .empty-cart {
        max-width: 600px;
        margin: 5rem auto;
        padding: 3rem 2rem;
        text-align: center;
        background: var(--white);
        border: 1px solid var(--sage);
    }

    .empty-cart-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-cart h2 {
        color: var(--green);
        font-family: var(--font-display);
        font-size: 1.8rem;
        margin-bottom: 0.75rem;
    }

    .empty-cart p {
        color: var(--slate);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .empty-cart-btn {
        max-width: 240px;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
        }
    }

    @media (max-width: 650px) {
        .cart-container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .cart-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .cart-item {
            grid-template-columns: 90px minmax(0, 1fr);
        }

        .cart-item-image {
            width: 90px;
            height: 90px;
        }

        .cart-item-actions {
            grid-column: 1 / -1;
            align-items: flex-start;
        }

        .quantity-form label {
            text-align: left;
        }

        .cart-item-total {
            grid-column: 1 / -1;
        }
    }
</style>
@endpush