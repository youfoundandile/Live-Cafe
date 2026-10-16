@extends('layouts.app')

@section('title', 'About us — LIVE café')

@push('styles')
<style>
    .ab-wrap { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }

    .ab-section { padding: 5rem 0; }
    .ab-section--band { background: var(--sage); }

    .ab-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
        gap: clamp(2rem, 6vw, 5rem);
        align-items: center;
    }

    .ab-grid--flip > .ab-media { order: -1; }

    /* ---- Headings: the words rise out of a line that draws itself ---- */
    .ab-label {
        margin: 0 0 0.8rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--green);
    }

    .ab-title-clip { overflow: hidden; padding: 0.1em 0 0.18em; }

    .ab-title {
        margin: 0;
        font-family: var(--font-display);
        font-size: clamp(2.1rem, 4.6vw, 3.4rem);
        line-height: 1.08;
        color: var(--green);
        transform: translateY(125%);
        transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .ab-rule {
        display: block;
        height: 2px;
        width: 100%;
        max-width: 260px;
        margin: 0.4rem 0 1.75rem;
        background: var(--accent);
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 1.4s 0.2s cubic-bezier(0.45, 0, 0.25, 1);
    }

    .ab-head.is-in .ab-title { transform: none; }
    .ab-head.is-in .ab-rule  { transform: scaleX(1); }

    .ab-text p {
        max-width: 46ch;
        margin: 0 0 1.1rem;
        font-size: 1.05rem;
        line-height: 1.75;
        color: var(--slate);
    }

    .ab-text p.ab-sign {
        margin-top: 1.5rem;
        font-family: var(--font-display);
        font-size: 1.25rem;
        letter-spacing: 0.08em;
        color: var(--green);
    }

    /* ---- Photos ---- */
    .ab-photo {
        position: relative;
        margin: 0;
        overflow: hidden;
        background: var(--sage);
    }

    .ab-photo img,
    .ab-ph {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.4s ease-out;
    }

    .ab-ph {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        color: var(--green);
    }

    /* Story: a large photo with a second one overlapping its corner */
    .ab-collage { position: relative; padding-bottom: 3.5rem; }

    .ab-photo--main { width: 80%; margin-left: auto; aspect-ratio: 4 / 5; }

    .ab-photo--inset {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50%;
        aspect-ratio: 1 / 1;
        border: 6px solid var(--base, #fff);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.14);
    }

    /* Community: one photo with an offset colour block behind it */
    .ab-frame { position: relative; margin: 0 0 0 14px; }

    .ab-frame::before {
        content: '';
        position: absolute;
        inset: 0;
        transform: translate(-14px, 14px);
        background: var(--accent);
    }

    .ab-photo--wide { aspect-ratio: 5 / 4; }

    /* ---- Three words ---- */
    .ab-pillars {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin: 2rem 0 2.25rem;
    }

    .ab-pillar {
        padding-top: 0.9rem;
        border-top: 3px solid var(--green);
    }

    .ab-pillar h3 {
        margin: 0 0 0.35rem;
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--green);
    }

    .ab-pillar p { margin: 0; font-size: 0.9rem; line-height: 1.55; color: var(--slate); }

    /* ---- Buttons ---- */
    .ab-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

    .ab-btn {
        display: inline-block;
        padding: 0.85rem 1.6rem;
        border-radius: 4px;
        border: 1.5px solid var(--green);
        background: var(--green);
        color: var(--white);
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: transform 0.2s, background 0.2s, color 0.2s;
    }

    .ab-btn--ghost { background: transparent; color: var(--green); }

    /* ---- Scroll reveal (script adds .ab-reveal, then .is-in) ---- */
    .ab-reveal { opacity: 0; transform: translateY(20px); }

    .ab-reveal.is-in {
        opacity: 1;
        transform: none;
        transition: opacity 0.9s ease, transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        transition-delay: var(--d, 0s);
    }

    /* Hover effects only on devices that can really hover */
    @media (hover: hover) {
        .ab-photo:hover img { transform: scale(1.06); }
        .ab-btn:hover { transform: translateY(-2px); }
        .ab-btn--ghost:hover { background: var(--green); color: var(--white); }
    }

    @media (max-width: 800px) {
        .ab-section { padding: 3.5rem 0; }
        .ab-wrap { padding: 0 1.25rem; }
        .ab-grid { grid-template-columns: 1fr; }
        .ab-grid--flip > .ab-media { order: 0; }
        .ab-pillars { grid-template-columns: 1fr; }
    }

    /* No JavaScript, or animations switched off: show everything straight away */
    @media (scripting: none), (prefers-reduced-motion: reduce) {
        .ab-title, .ab-rule, .ab-reveal { transform: none; opacity: 1; transition: none; }
        .ab-photo img { transition: none; }
    }
</style>
@endpush

@section('content')

@php
    // Shows a plain tile instead of a broken image if a photo file is missing.
    $photo = fn (string $file) => file_exists(public_path('images/about/'.$file))
        ? asset('images/about/'.$file)
        : null;

    $outside = $photo('Live outside.jpeg');
    $inside  = $photo('Live Inside 2.jpeg');
    $run     = $photo('Live members.jpg');
@endphp

{{-- Our story --}}
<section class="ab-section">
    <div class="ab-wrap ab-grid">

        <div class="ab-text">
            <div class="ab-head">
                <p class="ab-label">Our story</p>
                <div class="ab-title-clip">
                    <h1 class="ab-title">More than a café.<br>A community.</h1>
                </div>
                <span class="ab-rule"></span>
            </div>

            {{-- PROTOTYPE COPY: replace with Lloyd's own story for the final version --}}
            <p class="ab-reveal">
                Step into Live café , your ultimate healthy breakfast and lunch destination 
                featuring fresh-to-order sandwiches ,freshly pressed juices , nutrient packed smoothies, high protein shakes and specialty coffee.
            </p>
            <p class="ab-reveal">
                We offer a community based run club every Saturday at any fitness level and enjoy discounted drinks right after.
            </p>
            <p class="ab-reveal ab-sign">EAT. DRINK. LIVE.</p>
        </div>

        <div class="ab-media ab-collage ab-reveal">
            <figure class="ab-photo ab-photo--main">
                @if($outside)
                    <img src="{{ $inside }}" alt="The counter at Live Cafe, with green tiles and wooden shelves" loading="lazy">
                @else
                    <div class="ab-ph">Live Cafe</div>
                @endif
            </figure>

            <figure class="ab-photo ab-photo--inset">
                @if($inside)
                    <img src="{{ $outside }}" alt="The outside of Live Cafe in Sandton" loading="lazy">
                @else
                    <div class="ab-ph">Live Cafe</div>
                @endif
            </figure>
        </div>

    </div>
</section>

{{-- Our community --}}
<section class="ab-section ab-section--band">
    <div class="ab-wrap ab-grid ab-grid--flip">

        <div class="ab-media ab-reveal">
            <div class="ab-frame">
                <figure class="ab-photo ab-photo--wide">
                    @if($run)
                        <img src="{{ $run }}" alt="Members of the Live Cafe running club on a Saturday run" loading="lazy">
                    @else
                        <div class="ab-ph">Live Cafe</div>
                    @endif
                </figure>
            </div>
        </div>

        <div class="ab-text">
            <div class="ab-head">
                <p class="ab-label">Run together</p>
                <div class="ab-title-clip">
                    <h2 class="ab-title">Our community.</h2>
                </div>
                <span class="ab-rule"></span>
            </div>

            {{-- PROTOTYPE COPY: replace with Lloyd's own words for the final version --}}
            <p class="ab-reveal">
                Every Saturday, the cafe is where the run begins. People gather, warm up together and
                head out as one group. It is about more than distance. It is the people you meet and
                the moments you share along the way.
            </p>
            <p class="ab-reveal">Come for the miles, stay for the people.</p>

            <div class="ab-pillars">
                <div class="ab-pillar ab-reveal">
                    <h3>Connect</h3>
                    <p>Meet people who move the way you do.</p>
                </div>
                <div class="ab-pillar ab-reveal">
                    <h3>Move</h3>
                    <p>Weekly Saturday runs from the cafe.</p>
                </div>
                <div class="ab-pillar ab-reveal">
                    <h3>Share</h3>
                    <p>Good food, good coffee and good company.</p>
                </div>
            </div>

            <div class="ab-actions ab-reveal">
                <a href="{{ route('running.index') }}" class="ab-btn">Join a Saturday run</a>
                <a href="{{ route('shop.index') }}" class="ab-btn ab-btn--ghost">Browse the menu</a>
            </div>
        </div>

    </div>
</section>

<script>
(function () {
    var heads   = document.querySelectorAll('.ab-head');
    var reveals = document.querySelectorAll('.ab-reveal');

    function showAll() {
        heads.forEach(function (el) { el.classList.add('is-in'); });
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
        showAll();
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);   // plays once
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    heads.forEach(function (el) { observer.observe(el); });

    reveals.forEach(function (el) {
        // Stagger neighbours, such as the three pillars
        var index = Array.prototype.indexOf.call(el.parentElement.children, el);
        el.style.setProperty('--d', (Math.min(index, 4) * 0.1) + 's');
        observer.observe(el);
    });
})();
</script>
@endsection