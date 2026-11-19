
@extends('layouts.app')

@section('title', 'Shop — Live Cafe')

@section('content')
<div class="container shop-theme">

    <!-- Hero heading -->
    <header class="shop-hero">
        <h1 class="layered-heading">
            <span class="pink-letter">E</span><span class="green-letters">AT.</span><span class="pink-letter">D</span><span class="green-letters">RINK.</span><span class="pink-letter">L</span><span class="green-letters">IVE</span>
        </h1>
    </header>

    @if (session('error'))
        <div class="shop-flash">{{ session('error') }}</div>
    @endif

    <!-- Main category navigation -->
    <nav class="cat-nav" aria-label="Shop categories">
        @foreach($categories as $category)
            <a href="#category-{{ Str::slug($category->name) }}" class="cat-pill">
    <span class="nav-roll">
        <span>{{ $category->name }}</span>
        <span>{{ $category->name }}</span>
    </span>
</a>
        @endforeach
    </nav>

    <!-- Main category sections -->
    @forelse($categories as $category)
        <section
            id="category-{{ Str::slug($category->name) }}"
            class="shop-category-section"
        >

            <div class="section-head">
                <div class="section-head-clip">
                    <h2 class="section-title">{{ $category->name }}</h2>
                </div>
                <span class="section-line"></span>
            </div>

            @if($category->name === 'Merch')

                <div class="merch-coming-soon">
                    <span class="merch-coming-soon-label">LIVE RUN CLUB</span>
                    <h3>LIVE run-club merch coming soon</h3>
                    <p>Something special is on the way.</p>
                </div>

            @elseif($category->children->isNotEmpty())

                <div class="subcategory-grid">
                    @foreach($category->children as $subcategory)
                        <a
                            href="{{ route('shop.category', $subcategory->id) }}"
                            class="subcategory-card"
                        >
                            @php
    $categoryImages = [
        'Sandwiches' => 'images/menu/sandwiches.jpeg',
        'Pastries' => 'images/menu/pastries.jpeg',
        'Salad' => 'images/menu/salad.jpg',
        'Wraps' => 'images/menu/wraps.jpg',
        'Hot Drinks' => 'images/menu/hot-drinks.png',
        'Ice Ice Baby' => 'images/menu/ice-ice-baby.jpg',
        'Signature' => 'images/menu/signature.jpeg',
    ];

    $imagePath = $categoryImages[$subcategory->name] ?? null;
@endphp

<div class="subcategory-art">
    @if($imagePath && file_exists(public_path($imagePath)))
        <img
            src="{{ asset($imagePath) }}"
            alt="{{ $subcategory->name }}"
            class="subcategory-card-image"
        >
    @endif

</div>

                            <div class="subcategory-info">
    <h3>{{ $subcategory->name }}</h3>

    <span class="category-card-link">
        Explore menu &rarr;
    </span>
</div>
                        </a>
                    @endforeach
                </div>

            @endif

        </section>
    @empty
        <div class="empty-shop">
            <p>No categories are currently available.</p>
        </div>
    @endforelse

</div>

<script>
(function () {
    const heads = document.querySelectorAll('.section-head');

    if ('IntersectionObserver' in window) {
        const headObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                const scrolledPast = entry.rootBounds &&
                    entry.boundingClientRect.top < entry.rootBounds.top;

                if (entry.isIntersecting || scrolledPast) {
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            });
        }, {
            rootMargin: '0px 0px -20% 0px',
            threshold: 0
        });

        heads.forEach(function (head) {
            headObserver.observe(head);
        });
    } else {
        heads.forEach(function (head) {
            head.classList.add('is-visible');
        });
    }
})();
</script>
@endsection

@push('styles')
<style>
    /* ---------- Main page ---------- */

    body:has(.shop-theme) {
        background: #F8E8F0;
    }

    .shop-theme {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;

        --line-green: #78b24b;
        --slate: #4B4450;
        --green-light: #5B3A4E;
    }

    .shop-hero {
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .layered-heading {
        position: relative;
        font-family: var(--font-display);
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 0.5rem;
        text-shadow:
            2px 2px 0 #c76f8b,
            4px 4px 0 #ad5875;
    }

    .pink-letter {
        color: #c76f8b;
    }

    .green-letters {
        color: #2ab404;
    }

    .shop-flash {
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        text-align: center;
        font-size: 0.9rem;
        background: #fdecea;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    /* ---------- Main category navigation ---------- */

    .cat-nav {
        display: flex;
        gap: 1rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 4rem;
        width: 100%;
    }

    .cat-pill {
        display: inline-flex;
        padding: 0.65rem 1.7rem;
        background: var(--white);
        color: var(--ink);
        border: 1px solid var(--sage);
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: transform 0.25s ease, background 0.25s ease;
    }

    .cat-pill:active {
        transform: scale(0.97);
    }

    @media (hover: hover) {
    .cat-pill:hover,
    .cat-pill:focus-visible {
        transform: translateY(-3px);
        color: white;
    }

    /* Food and Merch – green */
    .cat-pill:nth-child(1):hover,
    .cat-pill:nth-child(1):focus-visible,
    .cat-pill:nth-child(3):hover,
    .cat-pill:nth-child(3):focus-visible {
        background: #2ab404;
    }

    /* Drinks – pink */
    .cat-pill:nth-child(2):hover,
    .cat-pill:nth-child(2):focus-visible {
        background: #c76f8b;
    }
}
    /* ---------- Main sections ---------- */

    .shop-category-section {
        margin-bottom: 4.5rem;
        scroll-margin-top: 100px;
    }

    .section-head {
        margin-bottom: 1.5rem;
    }

    .section-head-clip {
        overflow: hidden;
        padding: 0.15em 0 0.2em;
    }

    .section-title {
        margin: 0;
        font-family: var(--font-display);
        font-size: 1.75rem;
        line-height: 1.25;
        color: var(--ink);
        transform: translateY(130%);
        transition: transform 0.7s ease-in-out;
    }

    .section-line {
        display: block;
        height: 2px;
        background: var(--line-green);
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 1s ease-in-out;
    }

    .section-head.is-visible .section-title {
        transform: translateY(0);
        transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .section-head.is-visible .section-line {
        transform: scaleX(1);
        transition: transform 1.8s cubic-bezier(0.45, 0, 0.25, 1);
    }

    /* ---------- Subcategory cards ---------- */

    .subcategory-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 2rem;
    }

    .subcategory-card {
        display: block;
        overflow: hidden;
        background: var(--white);
        border: 1px solid var(--sage);
        border-radius: 8px;
        text-decoration: none;
        color: var(--ink);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    @media (hover: hover) {
        .subcategory-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.09);
        }
    }

    /* Temporary category artwork.
       These are placeholders until official category photos are supplied. */

    .subcategory-art {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    min-height: 0;
    overflow: hidden;
    background: #F8E8F0;
    display: block;
}

.subcategory-card-image {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    display: block;
    transform: none;
}

/* Hot Drinks */
.subcategory-card-image[src*="hot-drinks"] {
    object-position: center 65%;
}

/* Signature */
.subcategory-card-image[src*="signature"] {
    object-position: center 80%;
}

    .subcategory-art-label {
        position: relative;
        z-index: 1;
        font-family: var(--font-display);
        font-size: clamp(1.7rem, 3vw, 2.5rem);
        font-weight: 700;
        color: #4B4450;
        text-align: center;
        padding: 1rem;
    }

    /* Different temporary artwork colours */

    .art-pastries,
    .art-hot-drinks {
        background: linear-gradient(135deg, #E9C7B0, #F8E8F0);
    }

    .art-wraps,
    .art-ice-ice-baby {
        background: linear-gradient(135deg, #B8D2A0, #F8E8F0);
    }

    .art-salad,
    .art-signature {
        background: linear-gradient(135deg, #D0D4B4, #E9C7B0);
    }

    .subcategory-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
    }

    .subcategory-info h3 {
        margin: 0;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 600;
    }

    .category-card-link {
        color: #5B3A4E;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ---------- Merch ---------- */

    .merch-coming-soon {
        padding: 3.5rem 1.5rem;
        margin-top: 1.5rem;
        text-align: center;
        background: var(--white);
        border: 1px solid var(--sage);
        border-radius: 8px;
    }

    .merch-coming-soon-label {
        display: inline-block;
        margin-bottom: 1rem;
        color: var(--green-light);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.18em;
    }

    .merch-coming-soon h3 {
        margin: 0 0 0.75rem;
        color: var(--green-light);
        font-family: var(--font-display);
        font-size: clamp(1.4rem, 3vw, 2rem);
    }

    .merch-coming-soon p {
        margin: 0;
        color: var(--slate);
        font-size: 0.95rem;
    }

    .empty-shop {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 8px;
        border: 1px solid var(--sage);
    }

    /* ---------- Responsive layout ---------- */

    @media (max-width: 650px) {
        .shop-theme {
            padding: 0 1rem;
        }

        .subcategory-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .subcategory-info {
            padding: 1rem;
        }

        .cat-nav {
            margin-bottom: 3rem;
        }
    }

    /* ---------- Reduced motion ---------- */

    @media (prefers-reduced-motion: reduce) {
        .cat-pill,
        .subcategory-card,
        .section-title,
        .section-line {
            transition: none;
        }

        .section-title,
        .section-line {
            transform: none;
        }
    }

.subcategory-card-image {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transform: none;
}

/* Salad */
.subcategory-card-image[src*="salad"] {
    object-position: center center;
    transform: scale(1.08) translateX(-1.5%);
    transform-origin: center center;
}

/* Wraps */
.subcategory-card-image[src*="wraps"] {
    object-position: center center;
    transform: scale(1.08) translateX(-1.5%);
    transform-origin: center center;
}

/* Hot Drinks */
.subcategory-card-image[src*="hot-drinks"] {
    object-position: center center;
}

/* Signature */
.subcategory-card-image[src*="signature"] {
    object-position: center 75%;
}

</style>

<noscript>
    <style>
        .section-title,
        .section-line {
            transform: none;
        }
    </style>
</noscript>
@endpush
