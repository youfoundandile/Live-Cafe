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
    position: fixed;
    top: 90px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: min(90%, 520px);
    box-sizing: border-box;
}

        
.flash {
    box-sizing: border-box;
    width: 100%;
    padding: 1rem 1.5rem;
    border-radius: 8px;
}

        .flash-success { border-color: var(--success); background: #edfaf3; color: #1a5c38; }
        .flash-error   { border-color: var(--danger);  background: #fdf0ef; color: #7b1c14; }
        .flash-info    { border-color: var(--accent);  background: #f7faf4; color: #3a5c28; }

        
/* ── Shared Back Links ── */
.back-link {
    display: inline-block;
    font-size: 0.875rem;
    color: var(--slate);
    text-decoration: none;
    margin-bottom: 1.5rem;
    cursor: pointer;
}

.back-link:hover {
    color: var(--green);
}
       
/* ── Main content ── */
.main {
    flex: 1;
    padding: 3rem 0;
    background: #F8E8F0;
}


        /* ── Footer ── */
        .footer {
            background:#111;
            color: var(--white);
            padding: 4rem 0 0;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1.2fr 1.2fr;
            gap: 2.5rem;
        }

        .footer-logo-img {
            height: 64px;
            width: auto;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .footer-col h3 {
            margin: 0 0 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--accent);
        }

        .footer-about {
            margin: 0 0 1rem;
            max-width: 36ch;
            font-size: 0.92rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.78);
        }

        .footer-tagline {
            margin: 0;
            font-family: var(--font-display);
            font-size: 1.1rem;
            letter-spacing: 0.08em;
        }

        .footer-note {
            margin: 0;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.78);
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.2s;
        }

        .footer-links a:hover,
        .footer-links a:focus-visible {
            color: var(--white);
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding: 1.25rem 2rem;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 900px) {
            .footer-inner { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 560px) {
            .footer { padding-top: 3rem; }
            .footer-inner { grid-template-columns: 1fr; padding: 0 1.25rem 2rem; }
        }
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
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">Menu</a>
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
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">Menu</a>
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

        <div class="footer-col footer-brand">
            <img src="{{ asset('images/livecafelogo.jpeg') }}" alt="Live Cafe Logo" class="footer-logo-img">
            <p class="footer-about">
                Your healthy breakfast and lunch destination. Fresh sandwiches, pressed juices,
                smoothies and specialty coffee.
            </p>
            <p class="footer-tagline">EAT. DRINK. LIVE.</p>
        </div>

        <div class="footer-col">
            <h3>Explore</h3>
            <ul class="footer-links">
                <li><a href="{{ route('shop.index') }}">Menu</a></li>
                <li><a href="{{ route('running.index') }}">Running Club</a></li>
                <li><a href="{{ route('about') }}">About us</a></li>
                <li><a href="{{ route('contact') }}">Contact us</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Get in touch</h3>
            <ul class="footer-links">
                <li>
                    <a href="https://wa.me/27706729906?text={{ urlencode('Hi Live Cafe, I have a question.') }}"
                       target="_blank" rel="noopener">WhatsApp: 070 672 9906</a>
                </li>
                <li><a href="mailto:info@livecafe.co.za">info@livecafe.co.za</a></li>
                <li>
                    <a href="https://whatsapp.com/channel/0029Vb7QAlz47Xe88U53K11L"
                       target="_blank" rel="noopener">Run club WhatsApp channel</a>
                </li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Saturday runs</h3>
            <p class="footer-about">
                Our community run club meets every Saturday at the cafe, at any fitness level.
                Enjoy discounted drinks right after.
            </p>
            <p class="footer-note">Orders are collection only.</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Live Cafe. All rights reserved.</p>
    </div>
</footer>

   
@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.flash-wrap').forEach(function (wrapper) {
            setTimeout(function () {
                wrapper.style.transition = 'opacity 0.3s ease';
                wrapper.style.opacity = '0';

                setTimeout(function () {
                    wrapper.remove();
                }, 300);
            }, 1500);
        });
    });
</script>
</body>
</html>
