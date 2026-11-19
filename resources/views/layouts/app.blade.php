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
    <link rel="icon" href="{{ asset('images/livecafelogo.jpeg') }}" type="image/jpeg">
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
            background: #111;
            color: var(--white);
            padding: 4rem 0 0;
            margin-top: auto;
            border-top: 2px solid var(--accent);
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

        /* ── Utility ── */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        @media (max-width: 900px) {
            .footer-inner { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .nav-links { gap: 1rem; }
        }

        @media (max-width: 560px) {
            .footer { padding-top: 3rem; }
            .footer-inner { grid-template-columns: 1fr; padding: 0 1.25rem 2rem; }
        }

        /* Navigation rolling text animation */
        .nav-roll {
            display: inline-flex;
            flex-direction: column;
            height: 1.2em;
            overflow: hidden;
            vertical-align: middle;
        }

        .nav-roll span {
            display: block;
            height: 1.2em;
            line-height: 1.2em;
            flex-shrink: 0;
            transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
        }

        .nav-roll span:nth-child(2) {
            transform: translateY(0);
        }

        @media (hover: hover) {
            a:hover .nav-roll span,
            a:focus-visible .nav-roll span,
            .nav-signout:hover .nav-roll span,
            .nav-signout:focus-visible .nav-roll span {
                transform: translateY(-100%);
            }
        }

        /* Respect reduced-motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .nav-roll span {
                transition: none;
            }
        }

        /* Guest authentication popup */
        .guest-auth-modal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
        }

        .guest-auth-modal.is-open {
            display: flex;
        }

        .guest-auth-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(35, 24, 32, 0.65);
            backdrop-filter: blur(4px);
        }

        .guest-auth-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            background: #F8E8F0;
            border: 1px solid rgba(91, 58, 78, 0.2);
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(35, 24, 32, 0.25);
            text-align: center;
            animation: guestAuthReveal 0.25s ease;
        }

        .guest-auth-close {
            position: absolute;
            top: 0.7rem;
            right: 1rem;
            border: none;
            background: transparent;
            color: #5B3A4E;
            font-size: 1.8rem;
            cursor: pointer;
        }

        .guest-auth-eyebrow {
            margin-bottom: 0.6rem;
            color: #9D5875;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.18em;
        }

        .guest-auth-content h2 {
            margin-bottom: 0.8rem;
            color: #5B3A4E;
            font-family: var(--font-display);
            font-size: 1.8rem;
        }

        .guest-auth-message {
            margin-bottom: 1.8rem;
            color: #716773;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .guest-auth-actions {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
        }

        .guest-auth-actions a {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 1px solid #5B3A4E;
            border-radius: 30px;
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .guest-auth-login {
            background: #5B3A4E;
            color: #FFFFFF;
        }

        .guest-auth-register {
            background: transparent;
            color: #5B3A4E;
        }

        .guest-auth-login:hover {
            background: #79536A;
        }

        .guest-auth-register:hover {
            background: #5B3A4E;
            color: #FFFFFF;
        }

        @keyframes guestAuthReveal {
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
            .guest-auth-content {
                padding: 2.2rem 1.3rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .guest-auth-content {
                animation: none;
            }
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
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">
                            <span class="nav-roll">
                                <span>Menu</span>
                                <span>Menu</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('running.index') }}" class="{{ request()->is('running*') ? 'active' : '' }}">
                            <span class="nav-roll">
                                <span>Running Club</span>
                                <span>Running Club</span>
                            </span>
                        </a>
                    </li>

                    @if(auth()->user()->isCustomer())
                        <li>
                            <a href="{{ route('shop.orders.index') }}" class="{{ request()->is('shop/orders*') ? 'active' : '' }}">
                                <span class="nav-roll">
                                    <span>My Orders</span>
                                    <span>My Orders</span>
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('shop.cart.index') }}" class="{{ request()->is('shop/cart*') ? 'active' : '' }}">
                                <span class="nav-roll">
                                    <span>Cart</span>
                                    <span>Cart</span>
                                </span>

                                @php
                                    $cartCount = array_sum(session('cart', []));
                                @endphp

                                <span data-cart-count {{ $cartCount < 1 ? 'hidden' : '' }}>
                                    {{ $cartCount }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isStaff())
                        <li>
                            <a href="{{ route('pos.index') }}" class="{{ request()->is('pos*') ? 'active' : '' }}">
                                <span class="nav-roll">
                                    <span>POS</span>
                                    <span>POS</span>
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- Sign Out --}}
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-signout">
                                <span class="nav-roll">
                                    <span>Sign Out</span>
                                    <span>Sign Out</span>
                                </span>
                            </button>
                        </form>
                    </li>
                @else
                    {{-- ── Guest Session Nav ── --}}
                    <li>
                        <a href="{{ route('shop.index') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">
                            <span class="nav-roll">
                                <span>Menu</span>
                                <span>Menu</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('running.index') }}" class="{{ request()->is('running*') ? 'active' : '' }}">
                            <span class="nav-roll">
                                <span>Running Club</span>
                                <span>Running Club</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">
                            <span class="nav-roll">
                                <span>Sign in</span>
                                <span>Sign in</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('register') }}" class="nav-btn">
                            <span class="nav-roll">
                                <span>Join</span>
                                <span>Join</span>
                            </span>
                        </a>
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


<!-- Guest sign-in prompt -->
<div class="guest-auth-modal" id="guestAuthModal" aria-hidden="true">
    <div class="guest-auth-backdrop"></div>

    <div
        class="guest-auth-content"
        role="dialog"
        aria-modal="true"
        aria-labelledby="guestAuthTitle"
    >
        <button
            type="button"
            class="guest-auth-close"
            aria-label="Close"
        >&times;</button>

        <p class="guest-auth-eyebrow">LIVE CAFÉ</p>

        <h2 id="guestAuthTitle">Join our community</h2>

        <p class="guest-auth-message">
            Sign in or create an account to order from our menu.
        </p>

        <div class="guest-auth-actions">
            <a href="{{ route('login') }}" class="guest-auth-login">
                Sign In
            </a>

            <a href="{{ route('register') }}" class="guest-auth-register">
                Sign Up
            </a>
        </div>
    </div>
</div>

@stack('scripts')

<script>
    (() => {
        const modal = document.getElementById('guestAuthModal');

        if (!modal) return;

        const closeButton = modal.querySelector('.guest-auth-close');
        const backdrop = modal.querySelector('.guest-auth-backdrop');
        let previousFocus = null;

        // Open the popup
        window.openGuestAuthModal = function () {
            previousFocus = document.activeElement;

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            closeButton.focus();
        };

        // Close the popup
        function closeGuestAuthModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            if (previousFocus) {
                previousFocus.focus();
            }
        }

        closeButton.addEventListener('click', closeGuestAuthModal);
        backdrop.addEventListener('click', closeGuestAuthModal);

        // Close when Escape is pressed
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeGuestAuthModal();
            }
        });

        // Support any button or link marked as a guest-auth trigger
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-open-guest-auth]');

            if (trigger) {
                event.preventDefault();
                window.openGuestAuthModal();
            }
        });
    })();
</script>

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