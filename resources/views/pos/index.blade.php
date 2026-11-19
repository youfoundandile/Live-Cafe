@extends('layouts.app')

@section('title', 'POS — Live Cafe')

@push('styles')
<style>
    .pos-wrap [hidden] { display: none !important; }

    .pos-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1.5rem 3rem;
    }

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .pos-header h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--green);
    }

    .pos-header p {
        font-size: 0.875rem;
        color: var(--slate);
        margin-top: 0.2rem;
    }

    .pos-link {
        background: var(--green);
        color: var(--ink);
        padding: 0.7rem 1.25rem;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 4px;
    }

    .pos-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── Search ── */
    .pos-search input {
        width: 100%;
        padding: 0.9rem 1rem;
        font-size: 1rem;
        font-family: var(--font-body);
        border: 1.5px solid var(--sage);
        border-radius: 4px;
        background: var(--white);
        color: var(--ink);
    }

    .pos-search input:focus-visible,
    .pos-tab:focus-visible,
    .pos-product-btn:focus-visible,
    .stepper button:focus-visible,
    .btn-charge:focus-visible,
    .btn-clear:focus-visible {
        outline: 3px solid var(--ink);
        outline-offset: 2px;
    }

    /* ── Category tabs ── */
    .pos-tabs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding: 1rem 0 0.75rem;
        scrollbar-width: thin;
    }

    .pos-tab {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        min-height: 52px;
        padding: 0 1.4rem;
        background: var(--white);
        color: var(--ink);
        border: 1.5px solid var(--sage);
        border-radius: 999px;
        font-family: var(--font-body);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
    }

    .pos-tab.active {
        background: var(--green);
        border-color: var(--green);
    }

    .pos-tab-count {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--slate);
    }

    .pos-tab.active .pos-tab-count { color: var(--ink); }

    /* ── Product tiles ── */
    .pos-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(165px, 1fr));
        gap: 0.75rem;
    }

    .pos-product-btn {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 0.5rem;
        min-height: 104px;
        padding: 0.9rem 1rem;
        text-align: left;
        background: var(--white);
        border: 1.5px solid var(--sage);
        border-radius: 6px;
        font-family: var(--font-body);
        cursor: pointer;
        touch-action: manipulation;
    }

    .pos-product-btn:active { background: var(--base); }

    .pos-product-btn.selected {
        border-color: var(--green);
        background: var(--base);
    }

    .pos-product-btn .name {
        font-family: var(--font-display);
        font-size: 1rem;
        line-height: 1.25;
        color: var(--ink);
    }

    .pos-product-btn .price {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ink);
    }

    .pos-product-btn .low {
        font-size: 0.75rem;
        color: var(--danger);
    }

    .pos-product-btn .in-cart {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        min-width: 1.7rem;
        height: 1.7rem;
        padding: 0 0.4rem;
        display: grid;
        place-items: center;
        background: var(--green);
        color: var(--ink);
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .pos-empty {
        padding: 2rem 0;
        color: var(--slate);
        font-size: 0.95rem;
    }

    /* ── Cart ── */
    .pos-cart {
        position: sticky;
        top: calc(var(--nav-h) + 1rem);
        background: var(--white);
        border-top: 3px solid var(--green);
        padding: 1.25rem;
    }

    .pos-cart h2 {
        font-family: var(--font-display);
        font-size: 1.15rem;
        color: var(--green);
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .cart-empty {
        padding: 2rem 0;
        text-align: center;
        font-size: 0.9rem;
        color: var(--slate);
    }

    .cart-items {
        max-height: 42vh;
        overflow-y: auto;
        margin-bottom: 0.75rem;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.35rem 0.75rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }

    .cart-item-name { color: var(--ink); font-weight: 500; }
    .cart-item-price { font-weight: 600; text-align: right; }

    .stepper {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .stepper button {
        width: 44px;
        height: 44px;
        border: 1.5px solid var(--sage);
        border-radius: 6px;
        background: var(--white);
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
        touch-action: manipulation;
    }

    .stepper .qty-value {
        min-width: 2rem;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
    }

    .cart-item-remove {
        justify-self: end;
        align-self: center;
        background: none;
        border: none;
        color: var(--danger);
        font-family: var(--font-body);
        font-size: 0.85rem;
        padding: 0.6rem 0.25rem;
        cursor: pointer;
    }

    .cart-notice {
        margin-bottom: 0.6rem;
        padding: 0.6rem 0.75rem;
        background: #fdecea;
        color: var(--danger);
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-top: 2px solid var(--ink);
        font-size: 1.15rem;
        font-weight: 700;
    }

    .pay-method {
        margin-bottom: 0.9rem;
        padding: 0.6rem 0.8rem;
        background: var(--base);
        border-radius: 4px;
        font-size: 0.9rem;
        color: var(--slate);
    }

    .pay-method strong { color: var(--ink); }

    .btn-charge {
        width: 100%;
        min-height: 60px;
        background: var(--accent);
        color: var(--ink);
        border: none;
        border-radius: 6px;
        font-family: var(--font-body);
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-charge:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-clear {
        width: 100%;
        min-height: 48px;
        margin-top: 0.5rem;
        background: none;
        border: 1.5px solid var(--sage);
        border-radius: 6px;
        color: var(--slate);
        font-family: var(--font-body);
        font-size: 0.9rem;
        cursor: pointer;
    }

    @media (max-width: 900px) {
        .pos-grid { grid-template-columns: 1fr; }
        .pos-cart { position: static; }
        .cart-items { max-height: none; }
    }
</style>
@endpush

@section('content')
<div class="pos-wrap">

    <div class="pos-header">
        <div>
            <h1>Point of Sale</h1>
            <p>{{ auth()->user()->name }} {{ auth()->user()->surname }} &mdash; {{ now()->format('l, d F Y') }}</p>
        </div>
        <a href="{{ route('pos.sales.index') }}" class="pos-link">View sales &amp; orders</a>
    </div>

    <div class="pos-grid">

        {{-- Products --}}
        <div class="pos-products">

            @if($categories->isEmpty())
                <p class="pos-empty">No products are available to sell right now.</p>
            @else
                <div class="pos-search">
                    <input
                        type="search"
                        id="productSearch"
                        placeholder="Search all products"
                        autocomplete="off"
                        aria-label="Search all products"
                    >
                </div>

                <div class="pos-tabs" id="categoryTabs">
                    @foreach($categories as $category)
                        <button
                            type="button"
                            class="pos-tab {{ $loop->first ? 'active' : '' }}"
                            data-category="{{ $category->id }}"
                        >
                            {{ $category->name }}
                            <span class="pos-tab-count">{{ $category->products->count() }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="pos-product-grid" id="productGrid">
                    @foreach($categories as $category)
                        @foreach($category->products as $product)
                            <button
                                type="button"
                                class="pos-product-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->quantity }}"
                                data-category="{{ $category->id }}"
                                data-search="{{ strtolower($product->name) }}"
                                {{ $loop->parent->first ? '' : 'hidden' }}
                            >
                                <span class="name">{{ $product->name }}</span>
                                <span>
                                    <span class="price">R{{ number_format($product->price, 2) }}</span>
                                    @if($product->quantity <= 10)
                                        <span class="low"><br>{{ $product->quantity }} left</span>
                                    @endif
                                </span>
                                <span class="in-cart" hidden></span>
                            </button>
                        @endforeach
                    @endforeach
                </div>

                <p class="pos-empty" id="noResults" hidden>No products match your search.</p>
            @endif
        </div>

        {{-- Cart --}}
        <div class="pos-cart">
            <h2>Current sale</h2>

            <div id="cartNotice" class="cart-notice" role="status" hidden></div>

            <div id="cartEmpty" class="cart-empty">Tap a product to add it to the sale.</div>

            <div id="cartItems" class="cart-items" hidden></div>

            <div id="cartTotal" class="cart-total" hidden>
                <span>Total</span>
                <span id="totalAmount">R0.00</span>
            </div>

            <div id="cartControls" hidden>
                <p class="pay-method">Payment: <strong>Card</strong></p>

                <form method="POST" action="{{ route('pos.sale.store') }}" id="saleForm">
                    @csrf
                    <input type="hidden" name="payment_method" value="card">
                    <input type="hidden" name="items" id="itemsInput">
                    <button type="submit" class="btn-charge" id="chargeBtn">Charge card</button>
                </form>

                <button type="button" class="btn-clear" id="clearBtn">Clear sale</button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var cart = {};
    var tabs = document.querySelectorAll('.pos-tab');
    var tiles = document.querySelectorAll('.pos-product-btn');
    var search = document.getElementById('productSearch');
    var noResults = document.getElementById('noResults');
    var notice = document.getElementById('cartNotice');
    var noticeTimer = null;
    var activeCategory = tabs.length ? tabs[0].dataset.category : null;

    function money(cents) {
        return 'R' + (cents / 100).toFixed(2);
    }

    function el(tag, className, text) {
        var node = document.createElement(tag);
        if (className) { node.className = className; }
        if (text !== undefined) { node.textContent = text; }
        return node;
    }

    function showNotice(message) {
        notice.textContent = message;
        notice.hidden = false;
        clearTimeout(noticeTimer);
        noticeTimer = setTimeout(function () { notice.hidden = true; }, 3000);
    }

    /* ---- Filtering: one category at a time, or search across everything ---- */
    function applyFilter() {
        var query = search ? search.value.trim().toLowerCase() : '';
        var visible = 0;

        tiles.forEach(function (tile) {
            var show = query === ''
                ? tile.dataset.category === activeCategory
                : tile.dataset.search.indexOf(query) !== -1;
            tile.hidden = !show;
            if (show) { visible++; }
        });

        tabs.forEach(function (tab) {
            tab.classList.toggle('active', query === '' && tab.dataset.category === activeCategory);
        });

        if (noResults) { noResults.hidden = visible > 0; }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activeCategory = tab.dataset.category;
            if (search) { search.value = ''; }
            applyFilter();
        });
    });

    if (search) { search.addEventListener('input', applyFilter); }

    /* ---- Cart ---- */
    function addToCart(tile) {
        var id = tile.dataset.id;
        var stock = parseInt(tile.dataset.stock, 10);
        var current = cart[id] ? cart[id].qty : 0;

        if (current >= stock) {
            showNotice('Only ' + stock + ' of ' + tile.dataset.name + ' in stock.');
            return;
        }

        if (!cart[id]) {
            cart[id] = {
                id: id,
                name: tile.dataset.name,
                cents: Math.round(parseFloat(tile.dataset.price) * 100),
                stock: stock,
                qty: 0
            };
        }

        cart[id].qty++;
        renderCart();
    }

    function changeQty(id, delta) {
        if (!cart[id]) { return; }

        if (delta > 0 && cart[id].qty >= cart[id].stock) {
            showNotice('Only ' + cart[id].stock + ' of ' + cart[id].name + ' in stock.');
            return;
        }

        cart[id].qty += delta;
        if (cart[id].qty <= 0) { delete cart[id]; }
        renderCart();
    }

    function removeItem(id) {
        delete cart[id];
        renderCart();
    }

    function renderCart() {
        var ids = Object.keys(cart);
        var list = document.getElementById('cartItems');
        var totalCents = 0;

        list.replaceChildren();

        ids.forEach(function (id) {
            var item = cart[id];
            var lineCents = item.cents * item.qty;
            totalCents += lineCents;

            var row = el('div', 'cart-item');
            row.appendChild(el('div', 'cart-item-name', item.name));
            row.appendChild(el('div', 'cart-item-price', money(lineCents)));

            var stepper = el('div', 'stepper');
            var minus = el('button', null, '−');
            minus.type = 'button';
            minus.setAttribute('aria-label', 'Remove one ' + item.name);
            minus.addEventListener('click', function () { changeQty(id, -1); });

            var plus = el('button', null, '+');
            plus.type = 'button';
            plus.setAttribute('aria-label', 'Add one ' + item.name);
            plus.addEventListener('click', function () { changeQty(id, 1); });

            stepper.appendChild(minus);
            stepper.appendChild(el('span', 'qty-value', String(item.qty)));
            stepper.appendChild(plus);
            row.appendChild(stepper);

            var remove = el('button', 'cart-item-remove', 'Remove');
            remove.type = 'button';
            remove.addEventListener('click', function () { removeItem(id); });
            row.appendChild(remove);

            list.appendChild(row);
        });

        var hasItems = ids.length > 0;
        document.getElementById('cartEmpty').hidden = hasItems;
        list.hidden = !hasItems;
        document.getElementById('cartTotal').hidden = !hasItems;
        document.getElementById('cartControls').hidden = !hasItems;

        document.getElementById('totalAmount').textContent = money(totalCents);
        document.getElementById('chargeBtn').textContent = 'Charge card ' + money(totalCents);
        document.getElementById('itemsInput').value = JSON.stringify(
            ids.map(function (id) {
                return { product_id: parseInt(id, 10), quantity: cart[id].qty };
            })
        );

        tiles.forEach(function (tile) {
            var qty = cart[tile.dataset.id] ? cart[tile.dataset.id].qty : 0;
            var badge = tile.querySelector('.in-cart');
            badge.hidden = qty === 0;
            badge.textContent = qty;
            tile.classList.toggle('selected', qty > 0);
        });
    }

    tiles.forEach(function (tile) {
        tile.addEventListener('click', function () { addToCart(tile); });
    });

    document.getElementById('clearBtn').addEventListener('click', function () {
        if (Object.keys(cart).length && confirm('Clear this sale?')) {
            cart = {};
            renderCart();
        }
    });

    /* Stop a double tap from charging the customer twice */
    document.getElementById('saleForm').addEventListener('submit', function (event) {
        if (Object.keys(cart).length === 0) {
            event.preventDefault();
            return;
        }

        var button = document.getElementById('chargeBtn');
        button.disabled = true;
        button.textContent = 'Processing…';
    });

    renderCart();
    applyFilter();
})();
</script>
@endpush