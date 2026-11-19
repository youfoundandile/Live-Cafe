@extends('layouts.app')

@section('title', $product->name . ' — Live Cafe')

@section('content')
<div class="product-page">
    <div class="product-container">

        <a href="{{ route('shop.index') }}" class="back-link">← Back to menu</a>

        <div class="product-layout"

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
                    <button
        type="button"
        class="add-to-cart-btn"
        data-open-guest-auth
    >
        Sign in to order
    </button>               
     @endif
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .product-page { min-height: 70vh; background: #F8E8F0; }
    .product-container { max-width: 1100px; margin: 0 auto; padding: 1.5rem 2rem 4rem; }

    .product-flash {
    padding: 0.85rem 1rem;
    margin-bottom: 1.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.product-flash.is-dismissing {
    animation: dismiss-message 0.5s ease forwards;
}

@keyframes dismiss-message {
    to {
        opacity: 0;
        transform: translateY(-8px);
        visibility: hidden;
    }
}
    .product-flash-success { background: #e6f4ea; color: var(--green); border: 1px solid var(--green); }
    .product-flash-error { background: #fdecea; color: var(--danger); border: 1px solid var(--danger); }

    .product-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    align-items: start;
    max-width: 650px;
    margin: 0 auto;
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

        /* On page load: the image slides left while the details slide out from behind it */
    .product-image {
        z-index: 2;
        animation: image-slide 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    .product-details {
        position: relative;
        z-index: 1;
        animation: details-slide 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    /* Both start in the middle, with the image covering the details */
    @keyframes image-slide {
        from { transform: translateX(calc(50% + 1.5rem)); }
        to   { transform: translateX(0); }
    }

    @keyframes details-slide {
        from { transform: translateX(calc(-50% - 1.5rem)); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }

    /* On a phone the columns are stacked, so the details drop down from behind the image */
    @media (max-width: 700px) {
        .product-image   { animation: none; }
        .product-details { animation-name: details-drop; }

        @keyframes details-drop {
            from { transform: translateY(-2rem); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .product-image, .product-details { animation: none; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.flash-wrap .flash').forEach(function (message) {
        setTimeout(function () {
            message.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            message.style.opacity = '0';
            message.style.transform = 'translateY(-6px)';

            setTimeout(function () {
                message.remove();
            }, 250);
        }, 1500);
    });
});
</script>
@endpush