@extends('layouts.app')

@section('title', 'Live Cafe — Sandton')

@push('styles')
<style>
    /* ── Hero ── */
    .hero {
        background: var(--green);
        color: var(--white);
        padding: 6rem 2rem 5rem;
        position: relative;
        overflow: hidden;
    }

    .hero::after {
        content: '';
        position: absolute;
        right: -80px;
        top: -80px;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: rgba(232, 160, 32, 0.08);
        pointer-events: none;
        animation: drift 9s ease-in-out infinite alternate;
    }

    .hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-eyebrow {
        display: inline-block;
        background: var(--accent);
        color: var(--ink);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        margin-bottom: 1.5rem;
        letter-spacing: 0.02em;
    }

    .hero h1 {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 4rem);
        line-height: 1.1;
        max-width: 14ch;
        margin-bottom: 1.25rem;
        font-weight: 700;
    }

    .hero p {
        color: rgba(255,255,255,0.75);
        font-size: 1.05rem;
        max-width: 46ch;
        line-height: 1.65;
        margin-bottom: 2.5rem;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--ink);
        padding: 0.8rem 1.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: opacity 0.15s, transform 0.2s;
        font-family: var(--font-body);
    }

    .btn-primary:hover { opacity: 0.88; }

    .btn-outline {
        border: 1.5px solid rgba(255,255,255,0.45);
        color: var(--white);
        padding: 0.8rem 1.75rem;
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: border-color 0.15s, background 0.15s, transform 0.2s;
        font-family: var(--font-body);
    }

    .btn-outline:hover {
        border-color: var(--white);
        background: rgba(255,255,255,0.07);
    }

    /* ── Section shared ── */
    .section { padding: 5rem 2rem; }
    .section-alt { background: var(--sage); }

    .section-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--green);
        letter-spacing: 0.04em;
        margin-bottom: 0.6rem;
        text-transform: uppercase;
    }

    .section h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        color: var(--green);
        max-width: 22ch;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .section-lead {
        color: var(--slate);
        font-size: 1rem;
        max-width: 52ch;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    /* ── Feature cards ── */
    .feature-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .feature-card {
        background: var(--white);
        border-top: 3px solid var(--green);
        padding: 2rem;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .feature-card-icon {
        font-size: 1.8rem;
        margin-bottom: 1rem;
        display: inline-block;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .feature-card h3 {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--green);
        margin-bottom: 0.6rem;
    }

    .feature-card p {
        color: var(--slate);
        font-size: 0.9rem;
        line-height: 1.65;
        margin-bottom: 1.25rem;
    }

    .feature-card a {
        color: var(--green);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 1px;
        transition: color 0.15s;
    }

    .feature-card a:hover { color: var(--accent); }
    .feature-card a + a { margin-left: 1rem; }

    /* ── Shop strip ── */
    .shop-strip {
        max-width: 1200px;
        margin: 0 auto;
    }

    .shop-strip-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .shop-strip a.see-all {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 2px;
    }

    .product-row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .product-tile {
        background: var(--white);
        padding: 1.5rem;
        border-bottom: 3px solid transparent;
        transition: border-color 0.15s, transform 0.25s ease, box-shadow 0.25s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-tile:hover { border-color: var(--accent); }

    .product-tile-cat {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--slate);
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
    }

    .product-tile h4 {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--green);
        margin-bottom: 0.5rem;
    }

    .product-tile-price {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ink);
    }

    /* ── Running strip ── */
    .running-strip {
        max-width: 1200px;
        margin: 0 auto;
    }

    .running-strip-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .running-strip a.see-all {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 2px;
    }

    .event-row {
        display: flex;
        flex-direction: column;
        gap: 1px;
        background: #ddd;
    }

    .event-item {
        position: relative;
        background: var(--white);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        transition: background 0.2s ease;
    }

    .event-date {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--accent);
        min-width: 90px;
    }

    .event-name {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--green);
        flex: 1;
    }

    .event-location {
        font-size: 0.82rem;
        color: var(--slate);
    }

    .event-rsvp {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border: 1.5px solid var(--green);
        padding: 0.3rem 0.85rem;
        border-radius: 3px;
        white-space: nowrap;
        transition: background 0.15s, color 0.15s;
    }

    .event-rsvp:hover {
        background: var(--green);
        color: var(--white);
    }

    /* ── CTA band ── */
    .cta-band {
        background: var(--accent);
        padding: 4rem 2rem;
        text-align: center;
    }

    .cta-band h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        color: var(--ink);
        margin-bottom: 0.75rem;
    }

    .cta-band p {
        color: rgba(0,0,0,0.65);
        font-size: 1rem;
        max-width: 42ch;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    .btn-dark {
        display: inline-block;
        background: var(--green);
        color: var(--white);
        padding: 0.8rem 2rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: opacity 0.15s, transform 0.2s;
        font-family: var(--font-body);
    }

    .btn-dark:hover { opacity: 0.88; }

    /* ═════════════ Motion and finishing touches ═════════════ */

    /* Hero video slot (for the final version). Add a <video> inside .hero-media. */
    .hero-media {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .hero-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--green);
        opacity: 0.72;
    }

    /* Hero text rises in one piece at a time when the page loads */
    .hero-inner > * {
        opacity: 0;
        transform: translateY(22px);
        animation: rise 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .hero-inner > :nth-child(1) { animation-delay: 0.1s; }
    .hero-inner > :nth-child(2) { animation-delay: 0.25s; }
    .hero-inner > :nth-child(3) { animation-delay: 0.4s; }
    .hero-inner > :nth-child(4) { animation-delay: 0.55s; }

    /* The word "miles" gets underlined as if drawn with a pen */
    .underline-draw {
        background-image: linear-gradient(var(--accent), var(--accent));
        background-repeat: no-repeat;
        background-position: 0 92%;
        background-size: 0 0.1em;
        animation: underline 1s 1.1s ease forwards;
    }

    /* A running route draws itself across the hero, ending in a pulsing pin */
    .hero-route {
        position: absolute;
        right: 3%;
        bottom: 8%;
        width: min(560px, 46vw);
        z-index: 1;
        pointer-events: none;
        opacity: 0.6;
    }

    .route-line {
        stroke: var(--accent);
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-dasharray: 1;
        stroke-dashoffset: 1;
        animation: draw 3.2s 0.5s cubic-bezier(0.45, 0, 0.25, 1) forwards;
    }

    .route-start,
    .route-end {
        fill: var(--accent);
        opacity: 0;
        animation: pop 0.4s ease forwards;
    }

    .route-start { animation-delay: 0.5s; }
    .route-end   { animation-delay: 3.5s; }

    .route-pulse {
        fill: none;
        stroke: var(--accent);
        stroke-width: 2;
        opacity: 0;
        transform-box: fill-box;
        transform-origin: center;
        animation: ping 2.2s 3.6s ease-out infinite;
    }

    /* A small "keep scrolling" cue */
    .scroll-cue {
        position: absolute;
        left: 50%;
        bottom: 1.25rem;
        z-index: 2;
        width: 22px;
        height: 36px;
        margin-left: -11px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 12px;
        opacity: 0;
        animation: pop 0.6s 2s ease forwards;
    }

    .scroll-cue span {
        position: absolute;
        left: 50%;
        top: 6px;
        width: 4px;
        height: 7px;
        margin-left: -2px;
        border-radius: 2px;
        background: var(--accent);
        animation: scroll-dot 1.6s ease-in-out infinite;
    }

    /* Brand ribbon between the hero and the first section */
    .marquee {
        background: var(--ink);
        color: rgba(255, 255, 255, 0.85);
        overflow: hidden;
        padding: 0.9rem 0;
    }

    .marquee-track {
        display: flex;
        width: max-content;
        animation: marquee 34s linear infinite;
    }

    .marquee:hover .marquee-track { animation-play-state: paused; }

    .marquee-group { display: flex; flex-shrink: 0; }

    .marquee-group span {
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        font-family: var(--font-display);
        font-size: 1.1rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .marquee-group span::after {
        content: '';
        width: 6px;
        height: 6px;
        margin-left: 3rem;
        border-radius: 50%;
        background: var(--accent);
    }

    .marquee-group .marquee-live { color: var(--accent); }

    /* Section labels get a small accent line that draws in */
    .section-label::after {
        content: '';
        display: block;
        width: 48px;
        height: 2px;
        margin-top: 0.5rem;
        background: var(--accent);
        transform-origin: left center;
        transition: transform 0.9s 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .section-label.reveal::after { transform: scaleX(0); }
    .section-label.reveal.is-in::after { transform: scaleX(1); }

    /* Scroll reveals: JavaScript adds .reveal, then .is-in when the element comes into view */
    .reveal { opacity: 0; }

    .reveal.is-in {
        opacity: 1;
        animation: rise 0.8s cubic-bezier(0.22, 1, 0.36, 1) backwards;
        animation-delay: var(--d, 0s);
    }

        /* ── Video hero ── */
    .hero-video {
        position: relative;
        min-height: 78vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: #1d1a1c;
        color: #fff;
    }

    .hero-video__media {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    /* Darker on the left, where the words sit, and clearer on the right */
    .hero-video::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(20, 12, 18, 0.78) 0%, rgba(20, 12, 18, 0.45) 45%, rgba(20, 12, 18, 0.05) 100%);
    }

    /* Lines up with the rest of the page's content column */
    .hero-video__inner {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .hero-video__text { max-width: 36rem; }

    .hv-eyebrow {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        margin: 0 0 1.4rem;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #DAB4CB;
    }

    .hv-eyebrow-line {
        width: 48px;
        height: 2px;
        background: #DAB4CB;
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 0.9s 0.1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* Each line of the title rises out of its own clipping box, like the shop headings */
    .hv-title {
        margin: 0 0 1.25rem;
        font-family: var(--font-display);
        font-size: clamp(2.6rem, 6.4vw, 5.4rem);
        line-height: 1.02;
        font-weight: 700;
    }

    .hv-line {
        display: block;
        overflow: hidden;
        padding: 0.16em 0 0.12em;     /* room for the accent on É */
        margin: -0.16em 0 -0.12em;
    }

    .hv-line > span {
        display: inline-block;
        transform: translateY(115%);
        transition: transform 1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .hv-line:nth-child(2) > span { transition-delay: 0.14s; }

    .hv-title em {
        font-style: normal;
        color: #DAB4CB;
        background-image: linear-gradient(#DAB4CB, #DAB4CB);
        background-repeat: no-repeat;
        background-position: 0 94%;
        background-size: 0 0.06em;
        transition: background-size 1s 1s ease;     /* the underline draws after the words arrive */
    }

    .hv-sub {
        margin: 0 0 2rem;
        max-width: 30rem;
        font-size: clamp(1rem, 1.6vw, 1.2rem);
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.88);
    }

    .hv-fade {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .hv-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.95rem 1.6rem;
        background: #DAB4CB;
        color: var(--ink, #1b1b1b);
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        transition: transform 0.25s ease, background 0.25s ease;
    }

    .hv-btn span { transition: transform 0.25s ease; }

    /* Everything appears once the video is really playing (the page script adds .is-ready) */
    .hero-video.is-ready .hv-eyebrow-line { transform: scaleX(1); }
    .hero-video.is-ready .hv-line > span  { transform: none; }
    .hero-video.is-ready .hv-title em     { background-size: 100% 0.06em; }
    .hero-video.is-ready .hv-fade         { opacity: 1; transform: none; transition-delay: 0.55s; }
    .hero-video.is-ready .hv-eyebrow.hv-fade { transition-delay: 0.05s; }
    .hero-video.is-ready .hv-fade--2      { transition-delay: 0.75s; }

    @media (hover: hover) {
        .hv-btn:hover { transform: translateY(-2px); background: #fff; }
        .hv-btn:hover span { transform: translateX(4px); }
    }

    @media (max-width: 700px) {
        .hero-video { min-height: 70vh; align-items: flex-end; }
        .hero-video::after {
            background: linear-gradient(0deg, rgba(20, 12, 18, 0.85) 0%, rgba(20, 12, 18, 0.4) 60%, rgba(20, 12, 18, 0.1) 100%);
        }
        .hero-video__inner { padding: 3rem 1.25rem 2.5rem; }
    }

    /* No JavaScript, or animations switched off: just show everything */
    @media (scripting: none), (prefers-reduced-motion: reduce) {
        .hv-line > span, .hv-fade, .hv-eyebrow-line { transform: none; opacity: 1; transition: none; }
        .hv-title em { background-size: 100% 0.06em; transition: none; }
    }

    


    /* Hover effects only on devices that can really hover */
    @media (hover: hover) {
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
        }

        .feature-card:hover .feature-card-icon { transform: rotate(-10deg) scale(1.15); }

        .product-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
        }

        .product-tile-price::after {
            content: ' →';
            display: inline-block;
            opacity: 0;
            transform: translateX(-6px);
            transition: opacity 0.2s, transform 0.2s;
        }

        .product-tile:hover .product-tile-price::after {
            opacity: 1;
            transform: translateX(0);
        }

        .event-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
            transform: scaleY(0);
            transition: transform 0.25s ease;
        }

        .event-item:hover { background: #fbfaf4; }
        .event-item:hover::before { transform: scaleY(1); }

        .btn-primary:hover,
        .btn-outline:hover,
        .btn-dark:hover { transform: translateY(-2px); }
    }

    @keyframes rise {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: none; }
    }

    @keyframes underline { to { background-size: 100% 0.1em; } }
    @keyframes draw      { to { stroke-dashoffset: 0; } }
    @keyframes pop       { to { opacity: 1; } }
    @keyframes drift     { to { transform: translate(-30px, 24px); } }
    @keyframes marquee   { to { transform: translateX(-50%); } }

    @keyframes ping {
        0%   { opacity: 0.8; transform: scale(1); }
        100% { opacity: 0;   transform: scale(3.2); }
    }

    @keyframes scroll-dot {
        0%   { opacity: 0; transform: translateY(0); }
        30%  { opacity: 1; }
        100% { opacity: 0; transform: translateY(14px); }
    }

    @media (max-width: 700px) {
        .hero-route,
        .scroll-cue { display: none; }
    }

    @media (max-width: 600px) {
        .hero { padding: 4rem 1.25rem 3.5rem; }
        .section { padding: 3.5rem 1.25rem; }
        .event-item { flex-direction: column; align-items: flex-start; }
    }

    /* People who turn animations off in their system settings see the finished page */
    @media (prefers-reduced-motion: reduce) {
        .hero::after,
        .route-line, .route-start, .route-end, .route-pulse,
        .scroll-cue, .scroll-cue span,
        .marquee-track,
        .underline-draw { animation: none; }

        .hero-inner > * { opacity: 1; transform: none; animation: none; }
        .underline-draw { background-size: 100% 0.1em; }
        .route-line { stroke-dashoffset: 0; }
        .route-start, .route-end, .scroll-cue { opacity: 1; }
        .route-pulse { display: none; }
        .feature-card, .product-tile, .feature-card-icon { transition: none; }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}

<section class="hero-video" id="heroVideo">
    <video class="hero-video__media" autoplay muted loop playsinline preload="auto"
           aria-hidden="true" poster="{{ asset('images/hero-poster.jpg') }}">
        <source src="{{ asset('videos/live-cafe-hero.mp4') }}" type="video/mp4">
    </video>

    <div class="hero-video__inner">
        <div class="hero-video__text">
            <p class="hv-eyebrow hv-fade">
                <span class="hv-eyebrow-line"></span>Live Café · Sandton
            </p>

            <h1 class="hv-title">
                <span class="hv-line"><span>MORE THAN</span></span>
                <span class="hv-line"><span>A <em>CAFÉ.</em></span></span>
            </h1>

            <p class="hv-sub hv-fade">A place to connect, recharge and move together.</p>

            <div class="hv-fade hv-fade--2">
                <a href="{{ route('shop.index') }}" class="hv-btn">
                    Explore the Shop <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- What we offer --}}
<section class="section" id="offer">
    <div class="container">
        <p class="section-label">What we offer</p>
        <h2>One place for everything LIVE café.</h2>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-card-icon">☕</div>
                <h3>Order for collection</h3>
                <p>Browse our food, drinks, and merchandise. Place your order online and collect when it's ready — no queue, no wait.</p>
                <a href="{{ route('shop.index') }}">Browse the menu</a>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">🏃</div>
                <h3>Saturday running club</h3>
                <p>Weekly runs from the cafe every Saturday. RSVP so the organisers know you're coming, and bring friends along.</p>
                <a href="{{ route('running.index') }}">See upcoming runs</a>
            </div>
      <div class="feature-card">
    <div class="feature-card-icon">📣</div>
    @if ($latestAnnouncement)
        <h3>Announcements</h3>
        <p>{{ \Illuminate\Support\Str::limit($latestAnnouncement->description, 140) }}</p>
        <a href="{{ route('running.announcements.show', $latestAnnouncement) }}">Read more</a>
        <a href="{{ route('running.announcements.index') }}">View all</a>
    @else
        <h3>Announcements</h3>
        <p>Stay up to date with what's happening at LIVE café — new products, special events, and club news.</p>
        <a href="{{ route('running.announcements.index') }}">Read announcements</a>
    @endif
</div>

        </div>
    </div>
</section>

{{-- Shop strip --}}
<section class="section section-alt">
    <div class="shop-strip">
        <div class="shop-strip-header">
            <div>
                <p class="section-label">The menu</p>
                <h2>Thirst???</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="see-all">View full menu</a>
        </div>

        <div class="product-row">
            @forelse($featuredProducts as $product)
                <a href="{{ route('shop.product.show', $product) }}" class="product-tile">
                    <p class="product-tile-cat">{{ $product->category->name }}</p>
                    <h4>{{ $product->name }}</h4>
                    <span class="product-tile-price">R{{ number_format($product->price, 2) }}</span>
                </a>
            @empty
                <p style="color:var(--slate);font-size:0.9rem;">No products available right now.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Running strip --}}
<section class="section">
    <div class="running-strip">
        <div class="running-strip-header">
            <div>
                <p class="section-label">Running club</p>
                <h2>Upcoming events.</h2>
            </div>
            <a href="{{ route('running.index') }}" class="see-all">All events</a>
        </div>

        {{-- MAP (final version): add the route map with the run's meeting points here,
             for example an OpenStreetMap map using Leaflet, which needs no API key. --}}

        <div class="event-row">
            @forelse($upcomingEvents as $event)
                <div class="event-item">
                    <span class="event-date">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('D, d M') }}
                        &middot;
                        {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                    </span>
                    <span class="event-name">{{ $event->title }}</span>
                    <span class="event-location">{{ $event->address }}</span>
                    <a href="{{ route('running.events.show', $event) }}" class="event-rsvp">RSVP</a>
                </div>
            @empty
                <div class="event-item">
                    <span style="color:var(--slate);font-size:0.9rem;">No upcoming events — check back soon.</span>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA band --}}
@guest
<section class="cta-band">
    <h2>Join the LIVE café community.</h2>
    <p>Create an account to place orders, RSVP to runs, and track everything in one place.</p>
    <a href="{{ route('register') }}" class="btn-dark">Create an account</a>
</section>
@endguest

{{-- Scroll reveals: adds .reveal to the content, then .is-in when it scrolls into view --}}
<script>
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
    if (!('IntersectionObserver' in window)) { return; }

    var targets = document.querySelectorAll(
        '.section-label, .section h2, .section-lead, .feature-card, .product-tile, .event-item, .cta-band > *'
    );

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);   // plays once
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    targets.forEach(function (el) {
        // Stagger cards and tiles that sit next to each other
        var index = Array.prototype.indexOf.call(el.parentElement.children, el);
        el.style.setProperty('--d', (Math.min(index, 5) * 0.08) + 's');

        el.classList.add('reveal');
        observer.observe(el);
    });
})();
</script>

<script>
(function () {
    var hero  = document.getElementById('heroVideo');
    var video = hero && hero.querySelector('video');
    if (!hero) { return; }

    var shown = false;
    function show() {
        if (shown) { return; }
        shown = true;
        hero.classList.add('is-ready');
    }

    if (!video) { show(); return; }

    // The words appear when the video actually starts playing, not while the still image shows.
    if (!video.paused && video.readyState >= 3) {
        show();
    } else {
        video.addEventListener('playing', show, { once: true });
    }

    // If the browser blocks autoplay (battery saver, for example), still show the words.
    var attempt = video.play();
    if (attempt && attempt.catch) { attempt.catch(show); }

    // And if the video is very slow to load, don't leave the page wordless.
    setTimeout(show, 4000);
})();
</script>
@endsection