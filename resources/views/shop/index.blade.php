@extends('layouts.app')

@section('title', 'Shop — Live Cafe')

@section('content')
<div class="container" style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">

    <!-- Hero / Header Section -->
    <header style="margin-bottom: 3rem; text-align: center;">
        <h1 style="font-family: var(--font-display); font-size: 2.5rem; color: var(--green-light); margin-bottom: 0.5rem;">
            Live Cafe Store
        </h1>
        <p style="color: var(--slate); font-size: 1.1rem;">
            Fresh ingredients, delicious treats, and premium apparel. Order online and collect in store.
        </p>
    </header>

    <!-- Quick Category Anchors -->
    <nav style="display: flex; gap: 1rem; justify-content: center; align-items: center; flex-wrap: wrap; margin-bottom: 3rem; width: 100%;">
        @foreach($categories as $category)
            <a href="#category-{{ Str::slug($category->name) }}"
            style="background: var(--white); color: var(--ink); border: 1px solid var(--sage); padding: 0.6rem 1.5rem; border-radius: 20px; text-decoration: none; font-size: 0.95rem; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;"
            onmouseover="this.style.background='var(--accent)'; this.style.color='var(--white)';"
            onmouseout="this.style.background='var(--white)'; this.style.color='var(--ink)';">
                {{ $category->name }}
            </a>
        @endforeach
    </nav>

    <!-- Products Loop Grouped by Category -->
    @forelse($productsByCategory as $categoryName => $products)
        <section id="category-{{ Str::slug($categoryName) }}" style="margin-bottom: 4rem; scroll-margin-top: 100px;">

            <!-- Category Title -->
            <h2 style="font-family: var(--font-display); font-size: 1.75rem; color: var(--ink); margin-bottom: 1.5rem; border-bottom: 2px solid var(--sage); padding-bottom: 0.5rem;">
                {{ $categoryName }}
            </h2>

            <!-- Products Grid Layout -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem;">
                @foreach($products as $product)
                    @php
                        $soldOut = ! $product->prod_availability || $product->quantity < 1;
                    @endphp

                    <article style="position: relative; background: var(--white); border-radius: 8px; border: 1px solid var(--sage); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.05)';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)';">

                        @if($soldOut)
                            <span style="position: absolute; top: 0.75rem; right: 0.75rem; background: var(--danger); color: var(--white); font-size: 0.7rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 3px;">
                                Sold out
                            </span>
                        @endif

                        <!-- Product Text Info -->
                        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="font-size: 1.15rem; color: var(--ink); font-weight: 600; margin-bottom: 0.5rem;">
                                    {{ $product->name }}
                                </h3>
                                @if($product->description)
                                    <p style="color: var(--slate); font-size: 0.875rem; line-height: 1.4; margin-bottom: 1rem;">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Pricing and Actions -->
                            <div style="margin-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-size: 1.25rem; font-weight: 700; color: {{ $soldOut ? 'var(--slate)' : 'var(--ink)' }};">
                                    R{{ number_format($product->price, 2) }}
                                </span>

                                <a href="{{ route('shop.product.show', $product->id) }}" class="nav-btn" style="padding: 0.4rem 0.9rem; font-size: 0.8rem; margin: 0; display: inline-block; text-align: center;">
                                    View Details
                                </a>
                            </div>
                        </div>

                    </article>
                @endforeach
            </div>

        </section>
    @empty
        <!-- Fallback if database is completely blank -->
        <div style="text-align: center; padding: 4rem 2rem; background: var(--white); border-radius: 8px; border: 1px solid var(--sage);">
            <p style="color: var(--slate); font-size: 1.1rem;">No items are currently available in the shop.</p>
        </div>
    @endforelse

</div>
@endsection