@extends('layouts.app')

@section('title', 'Checkout — Live Cafe')

@section('content')
<div class="checkout-page">
    <div class="checkout-container">

        <a href="{{ route('shop.cart.index') }}" class="back-link">
    ← Back to cart
</a>

<div class="checkout-header">
            <p class="checkout-eyebrow">LIVE café</p>
            <h1>Checkout</h1>
            <p class="checkout-subtitle">Confirm your order and choose a collection time.</p>
        </div>

        <div class="checkout-layout">

            {{-- Main: collection details form --}}
            <section class="checkout-form-panel">
                <h2>Collection details</h2>

                <form action="{{ route('shop.checkout.store') }}" method="POST" id="checkout-form">
                    @csrf

                    
<label for="collection_date">Pickup date</label>
<input
    type="date"
    id="collection_date"
    min="{{ now()->toDateString() }}"
    value="{{ old('collection_time') ? \Illuminate\Support\Carbon::parse(old('collection_time'))->format('Y-m-d') : '' }}"
    required
>

<label for="collection_slot">Pickup time</label>
<select id="collection_slot" required disabled>
    <option value="">Choose a date first</option>
</select>

<input
    type="hidden"
    id="collection_time"
    name="collection_time"
    value="{{ old('collection_time') }}"
>

@error('collection_time')
    <p class="field-error">{{ $message }}</p>
@enderror

<p id="collection-message" class="checkout-note">
    Weekdays: 06:10–17:50. Saturdays: 06:10–14:50.
    Closed Sundays. Collection slots are every 10 minutes.
</p>

<p class="checkout-note">
    Orders are collection only from LIVE café, Sandton.
    No delivery is available.
</p>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('collection_date');
    const timeSelect = document.getElementById('collection_slot');
    const hiddenInput = document.getElementById('collection_time');
    const message = document.getElementById('collection-message');

    const previousValue = hiddenInput.value;
    const previousDate = previousValue ? previousValue.slice(0, 10) : '';
    const previousTime = previousValue ? previousValue.slice(11, 16) : '';

    dateInput.value = previousDate;

    function refreshSlots() {
        const selectedDate = dateInput.value;

        timeSelect.replaceChildren();
        hiddenInput.value = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Choose a collection time';
        timeSelect.appendChild(placeholder);

        if (!selectedDate) {
            timeSelect.disabled = true;
            message.textContent = 'Please select a collection date.';
            return;
        }

        // Parse the chosen calendar date without UTC conversion.
        const [year, month, day] = selectedDate.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const weekday = date.getDay(); // Sunday = 0

        if (weekday === 0) {
            dateInput.value = '';
            timeSelect.disabled = true;
            message.textContent =
                'Live Cafe is closed on Sundays. Please choose another date.';
            return;
        }

        const saturday = weekday === 6;
        const latestMinutes = saturday ? 14 * 60 + 50 : 17 * 60 + 50;

        const now = new Date();
        const isToday =
            now.getFullYear() === year &&
            now.getMonth() === month - 1 &&
            now.getDate() === day;

        for (let minutes = 6 * 60 + 10; minutes <= latestMinutes; minutes += 10) {
            const hour = Math.floor(minutes / 60);
            const minute = minutes % 60;

            if (isToday && minutes <= now.getHours() * 60 + now.getMinutes()) {
                continue;
            }

            const time = String(hour).padStart(2, '0') + ':' +
                String(minute).padStart(2, '0');

            const option = document.createElement('option');
            option.value = time;
            option.textContent = time;
            timeSelect.appendChild(option);
        }

        timeSelect.disabled = timeSelect.options.length <= 1;

        if (timeSelect.disabled) {
            message.textContent =
                'No collection slots remain for this date. Please choose another day.';
            return;
        }

        message.textContent = saturday
            ? 'Saturday collection: 06:10–14:50.'
            : 'Weekday collection: 06:10–17:50.';

        if (selectedDate === previousDate &&
            [...timeSelect.options].some(option => option.value === previousTime)) {
            timeSelect.value = previousTime;
        }

        updateHiddenValue();
    }

    function updateHiddenValue() {
        hiddenInput.value = dateInput.value && timeSelect.value
            ? dateInput.value + 'T' + timeSelect.value
            : '';
    }

    dateInput.addEventListener('change', refreshSlots);
    timeSelect.addEventListener('change', updateHiddenValue);

    document.getElementById('checkout-form').addEventListener('submit', event => {
        updateHiddenValue();

        if (!hiddenInput.value || timeSelect.disabled) {
            event.preventDefault();
            message.textContent = 'Please select a valid collection date and time.';
        }
    });

    refreshSlots();
});
</script>

                </form>
            </section>

            {{-- Sidebar: order summary --}}
            <aside class="checkout-summary">
                <h2>Order summary</h2>

                <ul class="summary-items">
                    
@foreach($products as $item)
    <li class="summary-item">
        @php
            $product = $item['product'];
            $image = $product->images->first();
        @endphp

        <div class="summary-item-image">
            @if($image)
                <img
                    src="{{ asset($image->path) }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                >
            @else
                <div class="summary-item-image-placeholder">
                    {{ $product->name }}
                </div>
            @endif
        </div>

        <div class="summary-item-info">
            <span class="summary-item-name">{{ $product->name }}</span>
            <span class="summary-item-qty">× {{ $item['quantity'] }}</span>
        </div>

        <span class="summary-item-price">
            R{{ number_format((float) $item['lineTotal'], 2) }}
        </span>
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
                
            </aside>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .checkout-page { min-height: 70vh; background: #F8E8F0 }
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

    
.summary-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.summary-item-image {
    width: 64px;
    height: 64px;
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 8px;
    background: #F0EEE9;
}

.summary-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.summary-item-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    font-size: 10px;
    text-align: center;
}

.summary-item-info {
    flex: 1;
    min-width: 0;
}

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

    @media (max-width: 900px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .checkout-summary { position: static; }
    }
</style>
@endpush