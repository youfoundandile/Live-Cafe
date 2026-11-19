
@extends('layouts.app')

@section('title', $category->name . ' — Live Cafe')

@section('content')
<div class="container category-page">

    <header class="category-hero">
    <a href="{{ route('shop.index') }}" class="back-link">
        &larr; Back to Shop
    </a>
</header>

    @if($products->isNotEmpty())

    <section class="category-showcase">

        <div class="category-image-panel">
            @if($imagePath && file_exists(public_path($imagePath)))
                <img
                    src="{{ asset($imagePath) }}"
                    alt="{{ $category->name }}"
                    class="category-feature-image"
                >
            @endif

            <div class="category-image-overlay">
                <h1 class="category-title">{{ $category->name }}</h1>
            </div>
        </div>

        <div class="category-menu-panel">
           
<div class="menu-list">

    @php
        $productGroups = $products->groupBy(function ($product) {
            $name = trim($product->name);

            if (preg_match('/\(\s*\d+(?:\.\d+)?\s*ml\s*\)$/i', $name)) {
                $baseName = preg_replace(
                    '/\s*\(\s*\d+(?:\.\d+)?\s*ml\s*\)$/i',
                    '',
                    $name
                );

                return 'sized:' . strtolower(trim($baseName));
            }

            return 'single:' . $product->id;
        });

        $productGroups = $productGroups->map(function ($variants) {
            return $variants->sortBy(function ($product) {
                $name = trim($product->name);

                if (preg_match('/\(\s*(\d+(?:\.\d+)?)\s*ml\s*\)$/i', $name, $matches)) {
                    return (float) $matches[1];
                }

                return PHP_INT_MAX;
            })->values();
        });
    @endphp

    @foreach($productGroups as $variants)

        @php
            $product = $variants->first();

            $displayName = preg_replace(
                '/\s*\(\s*\d+(?:\.\d+)?\s*ml\s*\)$/i',
                '',
                trim($product->name)
            );

            $soldOut = $variants->every(
    fn ($variant) => ! $variant->prod_availability || $variant->quantity < 1
);
            $cartQuantity = (int) (session('cart', [])[$product->id] ?? 0);
        @endphp

        <article
            class="menu-item {{ $soldOut ? 'is-sold-out' : '' }}"
            data-product-id="{{ $product->id }}"
            data-has-variants="{{ $variants->count() > 1 ? 'true' : 'false' }}"
            data-quantity="{{ $cartQuantity }}"
            data-available="{{ $soldOut ? 'false' : 'true' }}"
            data-stock="{{ $product->quantity }}"
            data-add-url="{{ route('shop.cart.add', $product->id) }}"
            data-update-url="{{ route('shop.cart.update', $product->id) }}"
        >

            <div class="menu-item-details">

                <div class="menu-item-heading">
                    <h2>{{ $displayName }}</h2>

                    <span class="menu-item-price">
                        R{{ number_format((float) $product->price, 2) }}
                    </span>
                </div>

                @if($product->description)
                    <p class="menu-item-description">
                        {{ $product->description }}
                    </p>
                @endif

            </div>

        @php
    $sizeOptions = $variants->map(function ($variant) use ($product) {
        $size = null;

        if (preg_match('/\(\s*(\d+(?:\.\d+)?)\s*ml\s*\)$/i', trim($variant->name), $matches)) {
            $size = $matches[1] . ' ml';
        }

        return [
            'id' => $variant->id,
            'size' => $size,
            'price' => (float) $variant->price,
            'difference' => (float) $variant->price - (float) $product->price,
            'available' => $variant->prod_availability && $variant->quantity > 0,
            'quantity' => (int) $variant->quantity,
            'cartQuantity' => (int) (session('cart', [])[$variant->id] ?? 0),
            'addUrl' => route('shop.cart.add', $variant->id),
            'updateUrl' => route('shop.cart.update', $variant->id),
        ];
    })->values();
@endphp

<div class="product-size-data" hidden>
    {{ json_encode($sizeOptions) }}
</div>

                    
<div class="menu-item-action">

    @if($variants->count() > 1)

        <button
            type="button"
            class="add-to-cart-btn"
            data-open-size-modal
            {{ $variants->every(fn ($variant) => ! $variant->prod_availability || $variant->quantity < 1) ? 'disabled' : '' }}
        >
            + Add
        </button>

        <span
            class="sold-out-text"
            {{ $variants->contains(fn ($variant) => $variant->prod_availability && $variant->quantity > 0) ? 'hidden' : '' }}
        >
            Unavailable
        </span>

    @else

        <button
            type="button"
            class="add-to-cart-btn"
            {{ $cartQuantity > 0 || $soldOut ? 'hidden' : '' }}
        >
            + Add
        </button>

        <div
            class="quantity-control"
            {{ $cartQuantity < 1 ? 'hidden' : '' }}
        >
            <button
                type="button"
                class="quantity-btn quantity-minus"
                aria-label="Remove one {{ $product->name }}"
            >
                &minus;
            </button>

            <span class="quantity-value" aria-live="polite">
                {{ $cartQuantity }}
            </span>

            <button
                type="button"
                class="quantity-btn quantity-plus"
                aria-label="Add one {{ $product->name }}"
                {{ $cartQuantity >= $product->quantity ? 'disabled' : '' }}
            >
                +
            </button>
        </div>

        <span
            class="sold-out-text"
            {{ ! $soldOut || $cartQuantity > 0 ? 'hidden' : '' }}
        >
            Unavailable
        </span>

    @endif

</div>

                    <p class="cart-feedback" role="status" aria-live="polite"></p>

                </article>

            @endforeach

                  </div>
        </div>

    </section>


        <div class="size-modal" id="sizeModal" aria-hidden="true">
            <div class="size-modal-backdrop"></div>

            <div
                class="size-modal-content"
                role="dialog"
                aria-modal="true"
                aria-labelledby="sizeModalTitle"
            >
                <button
                    type="button"
                    class="size-modal-close"
                    aria-label="Close size selection"
                >
                    &times;
                </button>

                <p class="size-modal-eyebrow">CHOOSE YOUR SIZE</p>

                <h2 id="sizeModalTitle"></h2>

                <div class="size-options" id="sizeOptions"></div>

                <p class="size-modal-feedback" id="sizeModalFeedback" role="status"></p>
            </div>
        </div>


    @else

        <div class="empty-category">
            <h2>Coming soon</h2>
            <p>We're preparing this menu. Check back soon!</p>
        </div>

    @endif

</div>
@endsection

@push('styles')
<style>
    body:has(.category-page) {
        background: #F8E8F0;
    }

    .category-page {
        max-width: 950px;
        margin: 2.5rem auto 4rem;
        padding: 0 2rem;
    }

    /* Header */

    .category-hero {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .category-hero .back-link {
        display: block;
        width: fit-content;
        margin-bottom: 1.8rem;
        color: #5B3A4E;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .category-hero .back-link:hover {
        color: #9D5875;
    }

    .category-title {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 600;
        letter-spacing: 0.04em;
        color: #5B3A4E;
        margin: 0 0 0.5rem;
        text-transform: uppercase;
    }

    .category-hero p {
        color: #716773;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Menu */

    .menu-list {
        background: transparent;
        border: none;
        padding: 0;
    }

    .menu-item {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        padding: 1.45rem 0;
        border-bottom: 1px solid rgba(91, 58, 78, 0.18);
    }

    .menu-item:last-child {
        border-bottom: none;
    }

    .menu-item-details {
        flex: 1;
        min-width: 0;
    }

    .menu-item-heading {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 1rem;
    }

    .menu-item-heading h2 {
        margin: 0;
        color: #3f3540;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 600;
    }

    .menu-item-price {
        flex-shrink: 0;
        color: #5B3A4E;
        font-size: 1.05rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .menu-item-description {
        margin: 0.4rem 0 0;
        color: #716773;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Add button */

    .menu-item-action {
        flex-shrink: 0;
    }

    .add-to-cart-btn {
        min-width: 76px;
        padding: 0.55rem 1rem;
        border: 1px solid #5B3A4E;
        border-radius: 30px;
        background: #5B3A4E;
        color: #FFFFFF;
        font-family: var(--font-body);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .add-to-cart-btn:hover {
        background: #79536A;
        transform: translateY(-2px);
    }

    .add-to-cart-btn.is-added {
        background: #86A96F;
        border-color: #86A96F;
    }

    /* Quantity selector */

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        min-width: 112px;
        padding: 0.25rem 0.4rem;
        border: 1px solid rgba(91, 58, 78, 0.35);
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.35);
    }

    .quantity-btn {
        display: grid;
        place-items: center;
        width: 29px;
        height: 29px;
        border: none;
        border-radius: 50%;
        background: #5B3A4E;
        color: white;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .quantity-btn:hover:not(:disabled) {
        background: #79536A;
        transform: scale(1.08);
    }

    .quantity-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .quantity-value {
        min-width: 12px;
        color: #5B3A4E;
        font-weight: 700;
        font-size: 0.95rem;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .sold-out-text {
        color: #8b5269;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .is-sold-out .menu-item-details {
        opacity: 0.6;
    }

    .cart-feedback {
        position: absolute;
        right: 0;
        bottom: 0.15rem;
        margin: 0;
        color: #5B3A4E;
        font-size: 0.72rem;
    }

    /* Empty category */

    .empty-category {
        text-align: center;
        padding: 4rem 2rem;
        color: #5B3A4E;
    }

    .empty-category h2 {
        font-family: var(--font-display);
    }

    .empty-category p {
        color: #716773;
    }

    [hidden] {
        display: none !important;
    }

    /* Mobile */

    @media (max-width: 650px) {
        .category-page {
            padding: 0 1rem;
            margin-top: 1.5rem;
        }

        .category-hero {
            margin-bottom: 2rem;
        }

        .category-title {
            font-size: 1.8rem;
        }

        .menu-item {
            gap: 0.75rem;
            padding: 1.25rem 0;
        }

        .menu-item-heading h2 {
            font-size: 1rem;
        }

        .menu-item-price {
            font-size: 0.95rem;
        }

        .add-to-cart-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

        .quantity-control {
            min-width: 100px;
            gap: 0.55rem;
        }
    }

    @media (max-width: 400px) {
        .menu-item {
            align-items: flex-start;
        }

        .menu-item-heading {
            flex-wrap: wrap;
            gap: 0.25rem 0.75rem;
        }

        .menu-item-action {
            padding-top: 0.1rem;
        }
    }

    
/* ── Category image and menu reveal ── */

.category-page {
    max-width: 1200px;
}

.category-hero {
    text-align: left;
    margin-bottom: 1.2rem;
}

.category-showcase {
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    position: relative;
    min-height: 650px;
    overflow: hidden;
    isolation: isolate;
}

/* Image panel */

.category-image-panel {
    position: relative;
    min-width: 0;
    min-height: 650px;
    overflow: hidden;
    background: #D0D4B4;
    z-index: 2;

    transform: translateX(100%);
    animation: categoryImageReveal 1.15s
        cubic-bezier(0.76, 0, 0.24, 1) forwards;
}

.category-feature-image {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.category-image-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: flex-end;
    padding: 2.5rem;
    background: linear-gradient(
        to top,
        rgba(30, 20, 25, 0.65),
        transparent 65%
    );
}

.category-image-overlay .category-title {
    margin: 0;
    color: #FFFFFF;
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

/* Menu panel */

.category-menu-panel {
    min-width: 0;
    padding: 1.2rem 2rem;
    background: rgba(255, 255, 255, 0.18);
    position: relative;
    z-index: 1;

    opacity: 0;
    animation: categoryMenuReveal 0.8s ease 0.3s forwards;
}

.category-menu-panel .menu-list {
    max-height: 650px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

/* Animation */

@keyframes categoryImageReveal {
    from {
        transform: translateX(100%);
    }

    to {
        transform: translateX(0);
    }
}

@keyframes categoryMenuReveal {
    from {
        opacity: 0;
        transform: translateX(25px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Mobile */

@media (max-width: 750px) {
    .category-showcase {
        grid-template-columns: 1fr;
        min-height: auto;
        overflow: visible;
    }

    .category-image-panel {
        min-height: 380px;
        transform: translateY(30px);
        animation: categoryMobileImageReveal 0.8s ease forwards;
    }

    .category-menu-panel {
        padding: 1rem 1.2rem;
    }

    .category-menu-panel .menu-list {
        max-height: none;
        overflow: visible;
    }

    .category-image-overlay {
        padding: 1.5rem;
    }
}

@keyframes categoryMobileImageReveal {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Accessibility */

@media (prefers-reduced-motion: reduce) {
    .category-image-panel,
    .category-menu-panel {
        animation: none;
        transform: none;
        opacity: 1;
    }
}


/* ── Size selection modal ── */

.size-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
}

.size-modal.is-open {
    display: flex;
}

.size-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(35, 24, 32, 0.65);
    backdrop-filter: blur(4px);
}

.size-modal-content {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    padding: 2.2rem;
    background: #F8E8F0;
    border: 1px solid rgba(91, 58, 78, 0.2);
    border-radius: 8px;
    box-shadow: 0 20px 60px rgba(35, 24, 32, 0.25);
    animation: sizeModalReveal 0.25s ease;
}

.size-modal-close {
    position: absolute;
    top: 0.8rem;
    right: 1rem;
    border: none;
    background: transparent;
    color: #5B3A4E;
    font-size: 1.8rem;
    cursor: pointer;
}

.size-modal-eyebrow {
    margin: 0 0 0.5rem;
    color: #9D5875;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.18em;
}

.size-modal-content h2 {
    margin: 0 0 1.5rem;
    color: #5B3A4E;
    font-family: var(--font-display);
    font-size: 1.8rem;
}

.size-options {
    display: grid;
    gap: 0.75rem;
}

.size-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    width: 100%;
    padding: 1rem 1.1rem;
    border: 1px solid rgba(91, 58, 78, 0.3);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.35);
    color: #5B3A4E;
    text-align: left;
    cursor: pointer;
    transition: background 0.2s ease, border-color 0.2s ease;
}

.size-option:hover:not(:disabled) {
    border-color: #5B3A4E;
    background: rgba(255, 255, 255, 0.75);
}

.size-option:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.size-option-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.size-option-name {
    font-weight: 700;
}

.size-option-stock {
    color: #8b5269;
    font-size: 0.75rem;
}

.size-option-price {
    font-weight: 700;
    white-space: nowrap;
}

.size-modal-feedback {
    min-height: 1.2rem;
    margin: 1rem 0 0;
    color: #B42318;
    font-size: 0.8rem;
}

@keyframes sizeModalReveal {
    from {
        opacity: 0;
        transform: translateY(12px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 480px) {
    .size-modal-content {
        padding: 1.8rem 1.3rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .size-modal-content {
        animation: none;
    }
}

</style>
@endpush

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const modal = document.getElementById('sizeModal');
    const modalTitle = document.getElementById('sizeModalTitle');
    const modalOptions = document.getElementById('sizeOptions');
    const modalFeedback = document.getElementById('sizeModalFeedback');

    const updateNavigationCartCount = (count) => {
        const cartCount = document.querySelector('[data-cart-count]');

        if (cartCount) {
            cartCount.textContent = count;
            cartCount.hidden = count < 1;
        }
    };

    const sendCartRequest = async (url, method, quantity = null) => {
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        };

        const options = {
            method,
            headers,
            credentials: 'same-origin'
        };

        if (method === 'PATCH') {
            headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify({ quantity });
        }

        const response = await fetch(url, options);

        let data = {};

        try {
            data = await response.json();
        } catch {
            throw new Error('We could not process your request. Please try again.');
        }

        if (!response.ok) {
            throw new Error(data.message || 'Something went wrong. Please try again.');
        }

        return data;
    };

    // ── Size modal ──

    const openSizeModal = (item) => {
        const sizeData = item.querySelector('.product-size-data');

        if (!sizeData) {
            return;
        }

        let variants;

        try {
            variants = JSON.parse(sizeData.textContent.trim());
        } catch {
            modalFeedback.textContent = 'We could not load the available sizes.';
            return;
        }

        modalTitle.textContent = item.querySelector('h2').textContent;
        modalFeedback.textContent = '';
        modalOptions.replaceChildren();

        variants.forEach((variant) => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'size-option';
            option.disabled = !variant.available;

            const details = document.createElement('span');
            details.className = 'size-option-details';

            const sizeName = document.createElement('span');
            sizeName.className = 'size-option-name';
            sizeName.textContent = variant.size || 'Regular';

            const stockInfo = document.createElement('span');
            stockInfo.className = 'size-option-stock';

            if (!variant.available) {
                stockInfo.textContent = 'Unavailable';
            } else if (variant.cartQuantity > 0) {
                stockInfo.textContent = `In cart: ${variant.cartQuantity}`;
            } else {
                stockInfo.textContent = 'Available';
            }

            const price = document.createElement('span');
            price.className = 'size-option-price';

            let priceText = `R${Number(variant.price).toFixed(2)}`;

            if (Number(variant.difference) > 0) {
                priceText += ` (+R${Number(variant.difference).toFixed(2)})`;
            }

            price.textContent = priceText;

            details.append(sizeName, stockInfo);
            option.append(details, price);

            option.addEventListener('click', async () => {
                if (!variant.available || option.disabled) {
                    return;
                }

                option.disabled = true;
                modalFeedback.textContent = 'Adding to your cart...';

                try {
                    const data = await sendCartRequest(
                        variant.addUrl,
                        'POST'
                    );

                    variant.cartQuantity = Number(data.quantity);

                    stockInfo.textContent = `In cart: ${variant.cartQuantity}`;
                    modalFeedback.textContent = 'Added to your cart!';

                    updateNavigationCartCount(Number(data.cart_count));

                } catch (error) {
                    modalFeedback.textContent = error.message;
                } finally {
                    option.disabled = !variant.available;
                }
            });

            modalOptions.appendChild(option);
        });

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        modal.querySelector('.size-modal-close').focus();
    };

    const closeSizeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    modal.querySelector('.size-modal-close').addEventListener('click', closeSizeModal);

    modal.querySelector('.size-modal-backdrop').addEventListener('click', closeSizeModal);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeSizeModal();
        }
    });

    // ── Product menu items ──

    // ── Product menu items ──

const isGuest = @json(auth()->guest());

document.querySelectorAll('.menu-item[data-product-id]').forEach((item) => {
    const addButton = item.querySelector('.add-to-cart-btn');

    // Guests must sign in or register before ordering.
    if (isGuest) {
        addButton.addEventListener('click', () => {
            window.openGuestAuthModal();
        });

        return;
    }

        // Multi-size products use the popup instead of the regular controls.
        if (item.dataset.hasVariants === 'true') {
            addButton.addEventListener('click', () => openSizeModal(item));
            return;
        }

        // Single-size products retain their existing quantity controls.
        const quantityControl = item.querySelector('.quantity-control');
        const quantityValue = item.querySelector('.quantity-value');
        const minusButton = item.querySelector('.quantity-minus');
        const plusButton = item.querySelector('.quantity-plus');
        const soldOutText = item.querySelector('.sold-out-text');
        const feedback = item.querySelector('.cart-feedback');

        let quantity = Number(item.dataset.quantity);
        const stock = Number(item.dataset.stock);
        const available = item.dataset.available === 'true';

        const render = () => {
            quantityValue.textContent = quantity;

            addButton.hidden = quantity > 0 || !available;
            quantityControl.hidden = quantity < 1;
            soldOutText.hidden = quantity > 0 || available;

            minusButton.disabled = quantity < 1;
            plusButton.disabled = quantity >= stock || !available;
        };

        const setBusy = (busy) => {
            item.querySelectorAll('button').forEach((button) => {
                button.disabled = busy ||
                    (button === plusButton && (quantity >= stock || !available));
            });
        };

        const showFeedback = (message, isError = false) => {
            feedback.textContent = message;
            feedback.style.color = isError ? '#B42318' : '#5B3A4E';
        };

        const changeQuantity = async (newQuantity, isAdding = false) => {
            if (item.dataset.busy === 'true') {
                return;
            }

            item.dataset.busy = 'true';
            setBusy(true);

            try {
                const data = isAdding
                    ? await sendCartRequest(item.dataset.addUrl, 'POST')
                    : await sendCartRequest(item.dataset.updateUrl, 'PATCH', newQuantity);

                quantity = Number(data.quantity);

                render();
                updateNavigationCartCount(Number(data.cart_count));

                showFeedback(isAdding ? 'Added to your cart!' : 'Cart updated.');

                if (isAdding) {
                    addButton.classList.add('is-added');

                    window.setTimeout(() => {
                        addButton.classList.remove('is-added');
                    }, 450);
                }

            } catch (error) {
                showFeedback(error.message, true);

            } finally {
                item.dataset.busy = 'false';
                setBusy(false);
                render();
            }
        };

        addButton.addEventListener('click', () => {
            changeQuantity(quantity + 1, true);
        });

        plusButton.addEventListener('click', () => {
            if (quantity < stock) {
                changeQuantity(quantity + 1);
            }
        });

        minusButton.addEventListener('click', () => {
            if (quantity > 0) {
                changeQuantity(quantity - 1);
            }
        });

        render();
    });
});
</script>
@endpush
