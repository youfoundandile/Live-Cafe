<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Live Cafe')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel = "icon" href = "{{ asset('images/livecafelogo.jpeg') }}" type = "image/jpeg" href = {{ asset('images/livecafelogo.jpeg') }}>
    <style>
        /* ── Tokens ── */
        :root {
            --green:       #B598A3; /* Pantone TCX Mauve Shadows */
            --accent:      #86A96F; /* Pantone TCX Matcha Green */
            --base:        #F0EEE9; /* Pantone TCX White Cloud */
            --green-light: #9D7E8B; 
            --sage:        #DCD3D7; 
            --slate:       #4A5568;
            --ink:         #1A1A1A;
            --white:       #FFFFFF;
            --danger:      #C0392B;
            --success:     #27AE60;

            --font-body:    'DM Sans', sans-serif;
            --font-display: 'Playfair Display', serif;
            --nav-h: 64px;
        }

        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--base);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Nav ── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--green);
            height: var(--nav-h);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            border-bottom: 2px solid var(--accent);
        }

        .nav-inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Fixed Image Scaling for Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-logo img {
            height: calc(var(--nav-h) - 20px);
            width: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.15s;
        }

        .nav-links a:hover,
        .nav-links a.active { color: var(--white); }
        .nav-links a.active { border-bottom: 2px solid var(--white); padding-bottom: 2px; }

        .nav-btn {
            background: var(--accent);
            color: var(--white) !important;
            padding: 0.45rem 1.1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.15s;
        }
        .nav-btn:hover { opacity: 0.88; }

        .nav-signout {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            font-weight: 500;
            font-family: var(--font-body);
            padding: 0;
            transition: color 0.15s;
        }
        .nav-signout:hover { color: var(--white); }

        /* ── Flash messages ── */
        .flash-wrap {
            width: 100%;
            max-width: 1200px;
            margin: 1rem auto 0;
            padding: 0 2rem;
        }

        .flash {
            padding: 0.85rem 1.2rem;
            border-left: 4px solid;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .flash-success { border-color: var(--success); background: #edfaf3; color: #1a5c38; }
        .flash-error   { border-color: var(--danger);  background: #fdf0ef; color: #7b1c14; }
        .flash-info    { border-color: var(--accent);  background: #f7faf4; color: #3a5c28; }

        /* ── Main content ── */
        .main { 
            flex: 1; 
            padding: 3rem 0;
        }

        /* ── Footer ── */
        .footer {
            background: var(--green);
            color: rgba(255,255,255,0.75);
            padding: 2.5rem 2rem;
            font-size: 0.85rem;
            margin-top: auto;
            border-top: 2px solid var(--accent);
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-logo img {
            height: 36px;
            width: auto;
            object-fit: contain;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .footer-links a {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            transition: color 0.15s;
        }
        .footer-links a:hover { color: var(--white); }

        /* ── Utility ── */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        @media (max-width: 768px) {
            .nav-links { gap: 1rem; }
            .footer-inner { flex-direction: column; text-align: center; }
            .footer-links { justify-content: center; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ asset('images/livecafelogo.jpeg') }}" alt="Live Cafe Logo">
            </a>

            <ul class="nav-links">
                @auth
                    {{-- ── Logged In Session Nav ── --}}
                    <li>
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">Shop</a>
                    </li>
                    <li>
                        <a href="{{ route('running.index') }}" class="{{ request()->is('running*') ? 'active' : '' }}">Running Club</a>
                    </li>
                    
                    @if(auth()->user()->isCustomer())
                        <li>
                            <a href="{{ route('shop.orders.index') }}" class="{{ request()->is('shop/orders*') ? 'active' : '' }}">My Orders</a>
                        </li>

                        <li>
                            <a href="{{ route('shop.cart.index') }}" class="{{ request()->is('shop/cart*') ? 'active' : '' }}">
                                Cart
                                 @php
                                 $cartCount = array_sum(session('cart', []));
                                 @endphp
                                @if($cartCount > 0)
                                   ({{ $cartCount }})
                                @endif
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isStaff())
                        <li>
                            <a href="{{ route('pos.index') }}" class="{{ request()->is('pos*') ? 'active' : '' }}">POS</a>
                        </li>
                    @endif

                    {{-- Formatted Sign Out Engine --}}
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-signout">Sign Out</button>
                        </form>
                    </li>
                @else
                    {{-- ── Guest Session Nav (Visible in new browsers) ── --}}
                    <li>
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">Shop</a>
                    </li>
                    <li>
                        <a href="{{ route('running.index') }}" class="{{ request()->is('running*') ? 'active' : '' }}">Running Club</a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Sign in</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="nav-btn">Join</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Flash message engine -->
    @if(session('success') || session('error') || session('info'))
        <div class="flash-wrap">
            @if(session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="flash flash-info">{{ session('info') }}</div>
            @endif
        </div>
    @endif

    <!-- Main dynamic page content slot -->
    <main class="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo">
                <img src="{{ asset('images/livecafelogo.jpeg') }}" alt="Live Cafe Logo">
            </div>

            <ul class="footer-links">
                <li><a href="{{ route('shop.index') }}">Shop</a></li>
                <li><a href="{{ route('running.index') }}">Running Club</a></li>
            </ul>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
