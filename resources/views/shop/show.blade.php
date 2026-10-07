@extends('layouts.app')

@section('title', $product->name . ' — Live Cafe')

@section('content')
<div class="product-page">
    <div class="product-container">

        <a href="{{ route('shop.index') }}" class="back-link">← Back to menu</a>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="product-flash product-flash-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="product-flash product-flash-error">{{ session('error') }}</div>
        @endif

        <div class="product-layout">

            {{-- Image --}}
            <div class="product-image">
                @php
                    $image = $product->images->sortBy('sort_order')->first();
                @endphp

                @if($image)
                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt_text ?? $product->name }}">
                @else
                    <div class="product-image-placeholder">Live Cafe</div>
                @endif

                @if(! $product->prod_availability || $product->quantity < 1)
                    <span class="sold-out-badge">Sold out</span>
                @endif
            </div>

            {{-- Details --}}
            <div class="product-details">
                @if($product->category)
                    <p class="product-category">{{ $product->category->name }}</p>
                @endif

                <h1>{{ $product->name }}</h1>

                <p class="product-price">R{{ number_format((float) $product->price, 2) }}</p>

                @if($product->description)
                    <p class="product-description">{{ $product->description }}</p>
                @endif

                @if(auth()->check() && auth()->user()->isCustomer())
                    @if($product->prod_availability && $product->quantity > 0)
                        <form action="{{ route('shop.cart.add', $product) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <button type="submit" class="add-to-cart-btn">Add to cart</button>
                        </form>
                    @else
                        <button type="button" class="add-to-cart-btn" disabled>Sold out</button>
                    @endif
                @elseif(auth()->check())
                    <p class="stock-note">Only customer accounts can order from the shop.</p>
                @else
                    <a href="{{ route('login') }}" class="add-to-cart-btn">Sign in to order</a>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .product-page { min-height: 70vh; }
    .product-container { max-width: 1100px; margin: 0 auto; padding: 1.5rem 2rem 4rem; }

    .back-link {
        display: inline-block;
        font-size: 0.875rem;
        color: var(--slate);
        text-decoration: none;
        margin-bottom: 1.5rem;
    }
    .back-link:hover { color: var(--green); }

    .product-flash {
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .product-flash-success { background: #e6f4ea; color: var(--green); border: 1px solid var(--green); }
    .product-flash-error { background: #fdecea; color: var(--danger); border: 1px solid var(--danger); }

    .product-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 3rem;
        align-items: start;
    }

    .product-image {
        position: relative;
        aspect-ratio: 1 / 1;
        background: var(--sage);
        overflow: hidden;
    }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-image-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--green);
        font-family: var(--font-display);
    }
    .sold-out-badge {
        position: absolute;
        top: 1rem; left: 1rem;
        background: var(--danger);
        color: var(--white);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 3px;
    }

    .product-category {
        color: var(--slate);
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .product-details h1 {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        color: var(--green);
        margin-bottom: 0.75rem;
    }
    .product-price {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 1.25rem;
    }
    .product-description {
        color: var(--slate);
        line-height: 1.7;
        max-width: 52ch;
        margin-bottom: 2rem;
    }

    .add-to-cart-form { margin-bottom: 0.5rem; }
    .add-to-cart-btn {
        display: inline-block;
        background: var(--green);
        color: var(--white);
        border: none;
        padding: 0.85rem 2rem;
        font-family: var(--font-body);
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.15s;
    }
    .add-to-cart-btn:hover { opacity: 0.9; }
    .add-to-cart-btn:disabled {
        background: var(--sage);
        color: var(--slate);
        cursor: not-allowed;
    }

    .stock-note { color: var(--slate); font-size: 0.8rem; }

    @media (max-width: 700px) {
        .product-layout { grid-template-columns: 1fr; gap: 1.5rem; }
    }
</style>
@endpush