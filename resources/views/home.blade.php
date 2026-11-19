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

        /* ═════════════ The cafe: moving menu cards ═════════════ */
    .mn-section { padding: 5rem 0; overflow: hidden; }

    .mn-head {
        max-width: 1200px;
        margin: 0 auto 2.5rem;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .mn-head h2 { margin-bottom: 0.75rem; }
    .mn-head .section-lead { margin-bottom: 0; }

    .mn-head-actions { display: flex; align-items: center; gap: 1.25rem; }

    .mn-head .see-all {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 2px;
    }

    .mn-pause {
        padding: 0.4rem 0.95rem;
        border: 1.5px solid var(--green);
        border-radius: 999px;
        background: transparent;
        color: var(--green);
        font: inherit;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
    }

    /* The window the cards move through. The mask fades both ends away. */
    .mn-viewport {
        --card-w: clamp(210px, 22vw, 270px);
        --gap: 1.5rem;
        overflow: hidden;
        padding: 1.5rem 0;
        -webkit-mask-image: linear-gradient(90deg, transparent 0, #000 7%, #000 93%, transparent 100%);
        mask-image: linear-gradient(90deg, transparent 0, #000 7%, #000 93%, transparent 100%);
    }

    /* The track holds two identical rows. Sliding it left by exactly half
       lands the copy where the original started, so the loop never jumps. */
    .mn-track {
        display: flex;
        width: max-content;
        animation: mn-scroll 60s linear infinite;
    }

    .mn-group {
        display: flex;
        flex-shrink: 0;
        gap: var(--gap);
        padding-right: var(--gap);   /* so both rows are exactly the same width */
    }

    @keyframes mn-scroll { to { transform: translateX(-50%); } }

    .mn-viewport.is-paused .mn-track,
    .mn-viewport:has(.mn-card:focus-visible) .mn-track { animation-play-state: paused; }

    /* ---- One card ---- */
    .mn-card {
        position: relative;
        flex: 0 0 var(--card-w);
        height: calc(var(--card-w) * 1.5);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 1.4rem;
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        text-align: left;
        cursor: pointer;
        font-family: var(--font-body);
        color: var(--t);
        background: linear-gradient(160deg, var(--c1), var(--c2));
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
    }

    /* A soft white glow rising from the bottom edge */
    .mn-card::after {
        content: '';
        position: absolute;
        left: -20%;
        right: -20%;
        bottom: -30%;
        height: 60%;
        background: radial-gradient(closest-side, rgba(255, 255, 255, 0.55), transparent);
        pointer-events: none;
    }

    .mn-card:focus-visible { outline: 3px solid var(--ink); outline-offset: 3px; }

    .mn-card-group {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        opacity: 0.8;
    }

    .mn-card-title {
        margin-top: 0.35rem;
        font-family: var(--font-display);
        font-size: clamp(1.5rem, 2.2vw, 1.9rem);
        line-height: 1.1;
    }

    .mn-card-media {
        position: relative;
        z-index: 1;
        margin-top: auto;
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mn-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Fix image framing on the 3D Salad and Wraps cards */
.mn-card[data-template="mn-tpl-2"] .mn-card-media img,
.mn-card[data-template="mn-tpl-3"] .mn-card-media img {
    transform: scale(1.08) translateX(-1.5%);
    transform-origin: center center;
}

    .mn-initial {
        font-family: var(--font-display);
        font-size: 4.5rem;
        line-height: 1;
        opacity: 0.85;
    }

    .mn-card-more {
        position: relative;
        z-index: 1;
        margin-top: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .mn-card-more i {
        width: 1.5rem;
        height: 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid currentColor;
        border-radius: 50%;
        font-style: normal;
        line-height: 1;
    }

    /* Hover effects only on devices that can really hover */
    @media (hover: hover) {

        .mn-card:hover {
    box-shadow: 0 18px 34px rgba(0, 0, 0, 0.18);
}
    }

    /* ---- The pop-up ---- */
    .mn-dialog {
    position: fixed;
    inset: 0;
    margin: auto;
    width: min(780px, calc(100% - 2rem));
    padding: 0;
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    background: var(--white);
    color: var(--ink);
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
}

    .mn-dialog::backdrop {
        background: rgba(25, 12, 20, 0.6);
        backdrop-filter: blur(3px);
    }

    .mn-dialog[open] { animation: mn-pop 0.35s cubic-bezier(0.22, 1, 0.36, 1); }

    @keyframes mn-pop {
        from { opacity: 0; transform: translateY(16px) scale(0.97); }
        to   { opacity: 1; transform: none; }
    }

    .mn-dialog-close {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        z-index: 2;
        width: 2.25rem;
        height: 2.25rem;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        color: var(--ink);
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
            display: flex;
        align-items: center;
    justify-content: center;
    padding: 0;
    appearance: none;
    box-shadow: none;
    text-decoration: none;
    }

    .mn-dialog-close::before,
.mn-dialog-close::after {
    content: none !important;
    display: none !important;
}

    .mn-sheet { display: grid; grid-template-columns: 1fr 1.1fr; min-height: 380px; }

    .mn-sheet-media {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(160deg, var(--c1), var(--c2));
        color: var(--t);
    }

    .mn-sheet-media img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mn-sheet-media {
    overflow: hidden;
}

.mn-sheet-media img {
    transform: scale(1.05) translateX(-0.5%);
}
.mn-sheet--wraps .mn-sheet-media img {
    transform: scale(1.12) translateX(-1.5%);
}

    .mn-initial--big { font-size: 9rem; }

    .mn-sheet-body {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 2.5rem 2rem 2rem;
    }

    .mn-sheet-group {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--slate);
    }

    .mn-sheet-body h3 {
        margin: 0;
        font-family: var(--font-display);
        font-size: 2rem;
        line-height: 1.1;
        color: var(--green);
    }

    .mn-sheet-text { margin: 0; line-height: 1.6; color: var(--slate); }

    .mn-items {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin: 0.25rem 0 0.5rem;
        padding: 0;
        list-style: none;
    }

    .mn-items li {
        padding: 0.25rem 0.75rem;
        border: 1px solid var(--sage);
        border-radius: 999px;
        font-size: 0.8rem;
    }

    .mn-cta { align-self: flex-start; margin-top: auto; }

    @media (max-width: 700px) {
        .mn-section { padding: 3.5rem 0; }
        .mn-head { padding: 0 1.25rem; }
        .mn-sheet { grid-template-columns: 1fr; }
        .mn-sheet-media { min-height: 200px; }
    }

    /* Animations switched off: the cards stop moving and can be swiped or scrolled instead */
    @media (prefers-reduced-motion: reduce) {
        .mn-track { animation: none; }
        .mn-viewport { overflow-x: auto; -webkit-mask-image: none; mask-image: none; }
        .mn-group[inert] { display: none; }
        .mn-pause { display: none; }
        .mn-card, .mn-card-media img { transition: none; }
        .mn-dialog[open] { animation: none; }
    }

    .mn-dialog {
    caret-color: transparent;
}

.mn-dialog * {
    caret-color: transparent;
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

{{-- The cafe: one section with a slowly moving row of menu cards --}}
@php
    // One entry per card. Subcategories are not in the database yet, so they live here for now.
    $menuCards = [
        [
            'group' => 'Food', 'title' => 'Sandwiches', 'slug' => 'sandwiches', 'category_id' => 5,
            'c1' => '#F4C26B', 'c2' => '#E8872F', 'text' => '#2A1608',
            'blurb' => 'On panini bread, with the fillings you love.',
            'items' => ['Club Anthem', 'Pescado', 'Special T', 'Grilled Chicken'],
        ],
        [
            'group' => 'Food', 'title' => 'Pastries', 'slug' => 'pastries', 'category_id' => 6,
            'c1' => '#F0CADB', 'c2' => '#CF8FB0', 'text' => '#3A1A2C',
            'blurb' => 'Croissants, loaves and sweet bakes to go with your coffee.',
            'items' => ['Almond Croissant', 'Butter Croissant', 'Butter Scone', 'Banana Loaf', 'Carrot Cake', 'Pecan Nut Squares'],
        ],
        [
            'group' => 'Food', 'title' => 'Salad', 'slug' => 'salad', 'category_id' => 7,
            'c1' => '#DDE6BF', 'c2' => '#86A56E', 'text' => '#1F2E17',
            'blurb' => 'Fresh and light, plain or with chicken or tuna.',
            'items' => ['Green Salad', 'Chicken Salad', 'Tuna Salad'],
        ],
        [
            'group' => 'Food', 'title' => 'Wraps', 'slug' => 'wraps', 'category_id' => 8,
            'c1' => '#F2B28C', 'c2' => '#E07A4F', 'text' => '#2A1008',
            'blurb' => 'Filling and easy to take with you.',
            'items' => ['Chicken Wrap'],
        ],
        // TODO: replace the drinks wording and items with Lloyd's drinks menu
        [
            'group' => 'Drinks', 'title' => 'Hot drinks', 'slug' => 'hot-drinks', 'category_id' => 9,
            'c1' => '#8A5A74', 'c2' => '#4B2A40', 'text' => '#FFFFFF',
            'blurb' => 'Coffee and more, made to order and served hot.',
            'items' => ['Americano', 'Cappuccino', 'Latte'],
        ],
        [
            'group' => 'Drinks', 'title' => 'Ice ice baby', 'slug' => 'ice-ice-baby', 'category_id' => 10,
            'c1' => '#A9C6EA', 'c2' => '#5D86C4', 'text' => '#0F2140',
            'blurb' => 'Cold and refreshing, for warm Sandton days.',
            'items' => ['Iced Coffee'],
        ],
        [
            'group' => 'Drinks', 'title' => 'Signature', 'slug' => 'signature', 'category_id' => 11,
            'c1' => '#E7A3C4', 'c2' => '#C86C97', 'text' => '#2B0D1C',
            'blurb' => 'Our own creations. Ask about the latest.',
            'items' => [],
        ],
    ];
    // Use the photo if the file exists. Otherwise the card shows a big letter.
    $menuCards = array_map(function ($card) {
        $extensions = ['jpg', 'jpeg', 'png'];
        $card['img'] = null;

        foreach ($extensions as $extension) {
            $file = 'images/menu/'.$card['slug'].'.'.$extension;

            if (file_exists(public_path($file))) {
                $card['img'] = asset($file);
                break;
            }
        }

        return $card;
    }, $menuCards);
@endphp

<section class="section mn-section" id="menu">

    <div class="mn-head">
        <div>
            <p class="section-label">The cafe</p>
            <h2>Fresh food and great coffee.</h2>
            <p class="section-lead">Order ahead, pay online and collect in store. Pick a favourite to see what is on the menu.</p>
        </div>
        <div class="mn-head-actions">
    <a href="{{ route('shop.index') }}" class="see-all">View full menu</a>
</div>
    </div>

    <div class="mn-viewport" id="mnViewport">
        <div class="mn-track">
            {{-- The same row twice. The copy is hidden from keyboards and screen readers. --}}
            @for ($g = 0; $g < 2; $g++)
                <div class="mn-group" @if($g === 1) inert aria-hidden="true" @endif>
                    @foreach($menuCards as $i => $card)
                        <button type="button" class="mn-card"
                                style="--c1: {{ $card['c1'] }}; --c2: {{ $card['c2'] }}; --t: {{ $card['text'] }};"
                                data-template="mn-tpl-{{ $i }}"
                                data-href="{{ route('shop.category', ['category' => $card['category_id']]) }}">
                            <span class="mn-card-group">{{ $card['group'] }}</span>
                            <span class="mn-card-title">{{ $card['title'] }}</span>
                            <span class="mn-card-media">
                                @if($card['img'])
                                    <img src="{{ $card['img'] }}" alt="" loading="lazy">
                                @else
                                    <span class="mn-initial">{{ mb_substr($card['title'], 0, 1) }}</span>
                                @endif
                            </span>
                            <span class="mn-card-more">Explore <i aria-hidden="true">+</i></span>
                        </button>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

</section>

{{-- What opens when a card is clicked: one template per card, one shared pop-up --}}
@foreach($menuCards as $i => $card)
    <template id="mn-tpl-{{ $i }}">
        <div class="mn-sheet {{ $card['slug'] === 'wraps' ? 'mn-sheet--wraps' : '' }}" style="--c1: ...">
            <div class="mn-sheet-media">
                @if($card['img'])
                    <img src="{{ $card['img'] }}" alt="{{ $card['title'] }} at Live Cafe">
                @else
                    <span class="mn-initial mn-initial--big">{{ mb_substr($card['title'], 0, 1) }}</span>
                @endif
            </div>
            <div class="mn-sheet-body">
                <p class="mn-sheet-group">{{ $card['group'] }}</p>
                <h3 id="mnTitle">{{ $card['title'] }}</h3>
                <p class="mn-sheet-text">{{ $card['blurb'] }}</p>

                @if(count($card['items']))
                    <ul class="mn-items">
                        @foreach($card['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif

                <a class="btn-dark mn-cta" href="{{ route('shop.category', ['category' => $card['category_id']]) }}">See it in the shop →</a>
            </div>
        </div>
    </template>
@endforeach

<dialog class="mn-dialog" id="mnDialog" aria-labelledby="mnTitle">
    <button type="button" class="mn-dialog-close" aria-label="Close"
            onclick="this.closest('dialog').close()">×</button>

    <div class="mn-dialog-content"></div>
</dialog>

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

<script>
(function () {
    var viewport = document.getElementById('mnViewport');
    var dialog   = document.getElementById('mnDialog');
    if (!viewport || !dialog) { return; }

    var holder = dialog.querySelector('.mn-dialog-content');

    // Clicking a card: copy its template into the pop-up and open it
    viewport.addEventListener('click', function (e) {
        var card = e.target.closest('.mn-card');
        if (!card) { return; }

        // Very old browsers without <dialog> just go to the shop
        if (typeof dialog.showModal !== 'function') {
            window.location.href = card.dataset.href;
            return;
        }

        var template = document.getElementById(card.dataset.template);
        holder.replaceChildren(template.content.cloneNode(true));
        viewport.classList.add('is-paused');
        dialog.showModal();
    });

    // Clicking the dark area outside the box closes it (Esc and the × close it already)
    dialog.addEventListener('click', function (e) {
        if (e.target === dialog) { dialog.close(); }
    });

    // When it closes, the cards carry on moving unless the visitor pressed Pause
    dialog.addEventListener('close', function () {
    viewport.classList.remove('is-paused');});
})();
</script>
@endsection