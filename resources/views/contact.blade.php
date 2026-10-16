@extends('layouts.app')

@section('title', 'Contact us — Live Cafe')

@push('styles')
<style>
    .ct-wrap { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    .ct-section { padding: 4.5rem 0 2rem; }
    .ct-section--last { padding-bottom: 5.5rem; }

    /* ---- Heading: the words rise out of a line that draws itself ---- */
    .ct-label {
        margin: 0 0 0.8rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--green);
    }

    .ct-title-clip { overflow: hidden; padding: 0.1em 0 0.18em; }

    .ct-title {
        margin: 0;
        font-family: var(--font-display);
        font-size: clamp(2.3rem, 5.2vw, 3.8rem);
        line-height: 1.06;
        color: var(--green);
        transform: translateY(125%);
        transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .ct-rule {
        display: block;
        height: 2px;
        width: 100%;
        max-width: 260px;
        margin: 0.4rem 0 1.5rem;
        background: var(--accent);
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 1.4s 0.2s cubic-bezier(0.45, 0, 0.25, 1);
    }

    .ct-head.is-in .ct-title { transform: none; }
    .ct-head.is-in .ct-rule  { transform: scaleX(1); }

    .ct-intro {
        max-width: 50ch;
        margin: 0;
        font-size: 1.1rem;
        line-height: 1.7;
        color: var(--slate);
    }

    /* ---- Contact cards ---- */
    .ct-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .ct-card {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        padding: 1.75rem;
        background: var(--white);
        border-top: 3px solid var(--green);
        text-decoration: none;
        color: inherit;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .ct-card:focus-visible { outline: 3px solid var(--accent); outline-offset: 3px; }

    .ct-card-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--slate);
    }

    .ct-card-title {
        font-family: var(--font-display);
        font-size: 1.45rem;
        color: var(--green);
    }

    .ct-card-value {
        font-size: 1rem;
        color: var(--ink);
        word-break: break-word;
    }

    .ct-card-go {
        margin-top: 1.1rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--green);
    }

    .ct-card-go i {
        display: inline-block;
        font-style: normal;
        transition: transform 0.25s ease;
    }

    /* A small pulsing dot on the run club channel card, for "live updates" */
    .ct-dot {
        position: relative;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
    }

    .ct-dot::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: var(--accent);
        animation: ct-ping 2s ease-out infinite;
    }

    @keyframes ct-ping {
        0%   { opacity: 0.7; transform: scale(1); }
        100% { opacity: 0;   transform: scale(3.4); }
    }

    /* ---- Address and hours (only shown once filled in) ---- */
    .ct-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 2.5rem;
        margin-top: 3.5rem;
        padding-top: 2.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.12);
    }

    .ct-info h2 {
        margin: 0 0 0.8rem;
        font-family: var(--font-display);
        font-size: 1.3rem;
        color: var(--green);
    }

    .ct-info p, .ct-info dd { margin: 0; line-height: 1.7; color: var(--slate); }
    .ct-info dl { margin: 0; }
    .ct-info dt { font-weight: 600; color: var(--ink); }
    .ct-info dd + dt { margin-top: 0.6rem; }

    .ct-info a {
        display: inline-block;
        margin-top: 0.7rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
    }

    /* ---- Questions ---- */
    .ct-faq { max-width: 760px; margin-top: 2.5rem; }

    .ct-faq h2 {
        margin: 0 0 1.25rem;
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        color: var(--green);
    }

    .ct-faq details { border-top: 1px solid rgba(0, 0, 0, 0.14); }
    .ct-faq details:last-of-type { border-bottom: 1px solid rgba(0, 0, 0, 0.14); }

    .ct-faq summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 0;
        font-weight: 600;
        color: var(--ink);
        cursor: pointer;
        list-style: none;
    }

    .ct-faq summary::-webkit-details-marker { display: none; }

    .ct-faq summary::after {
        content: '+';
        flex-shrink: 0;
        font-size: 1.4rem;
        font-weight: 400;
        color: var(--green);
        transition: transform 0.25s ease;
    }

    .ct-faq details[open] summary::after { transform: rotate(45deg); }

    .ct-faq summary:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }

    .ct-faq details p {
        margin: 0 0 1.2rem;
        max-width: 60ch;
        line-height: 1.7;
        color: var(--slate);
    }

    /* ---- Scroll reveal (script adds .ct-reveal, then .is-in) ---- */
    .ct-reveal { opacity: 0; transform: translateY(20px); }

    .ct-reveal.is-in {
        opacity: 1;
        transform: none;
        transition: opacity 0.9s ease, transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
        transition-delay: var(--d, 0s);
    }

    /* Hover effects only on devices that can really hover */
    @media (hover: hover) {
        .ct-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
        }

        .ct-card:hover .ct-card-go i { transform: translateX(5px); }
    }

    @media (max-width: 700px) {
        .ct-wrap { padding: 0 1.25rem; }
        .ct-section { padding: 3rem 0 1.5rem; }
    }

    /* No JavaScript, or animations switched off: show everything straight away */
    @media (scripting: none), (prefers-reduced-motion: reduce) {
        .ct-title, .ct-rule, .ct-reveal { transform: none; opacity: 1; transition: none; }
        .ct-dot::after { animation: none; display: none; }
        .ct-card, .ct-card-go i, .ct-faq summary::after { transition: none; }
    }
</style>
@endpush

@section('content')

@php
    // ──────── Live cafe contact details. ────────
    $whatsappNumber  = '27706729906';    
    $whatsappDisplay = '070 672 9906';   // how the number looks on the page
    $email           = 'info@livecafe.co.za';
    $channelUrl      = 'https://whatsapp.com/channel/0029Vb7QAlz47Xe88U53K11L';
    
    //Null for now
    $address = null;   // e.g. '12 Example Street, Sandton'
    $hours   = null;   // e.g. ['Monday to Friday' => '06:30 – 17:00', 'Saturday' => '06:00 – 14:00']
@endphp

<section class="ct-section">
    <div class="ct-wrap">

        <div class="ct-head">
            <p class="ct-label">Get in touch</p>
            <div class="ct-title-clip">
                <h1 class="ct-title">Let's talk.</h1>
            </div>
            <span class="ct-rule"></span>
        </div>

        <p class="ct-intro ct-reveal">
            A question about an order, the menu or the Saturday run? Send us a message and we will get back to you.
        </p>

        <div class="ct-cards">

            <a class="ct-card ct-reveal"
               href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hi Live Cafe, I have a question.') }}"
               target="_blank" rel="noopener">
                <span class="ct-card-label">WhatsApp</span>
                <span class="ct-card-title">Chat with us</span>
                <span class="ct-card-value">{{ $whatsappDisplay }}</span>
                <span class="ct-card-go">Open WhatsApp <i aria-hidden="true">→</i></span>
            </a>

            <a class="ct-card ct-reveal" href="mailto:{{ $email }}">
                <span class="ct-card-label">Email</span>
                <span class="ct-card-title">Write to us</span>
                <span class="ct-card-value">{{ $email }}</span>
                <span class="ct-card-go">Send an email <i aria-hidden="true">→</i></span>
            </a>

            <a class="ct-card ct-reveal" href="{{ $channelUrl }}" target="_blank" rel="noopener">
                <span class="ct-card-label"><span class="ct-dot" aria-hidden="true"></span>Run club</span>
                <span class="ct-card-title">Join the channel</span>
                <span class="ct-card-value">News and updates on the Saturday runs</span>
                <span class="ct-card-go">Open the WhatsApp channel <i aria-hidden="true">→</i></span>
            </a>

        </div>

        @if($address || $hours)
            <div class="ct-info ct-reveal">
                @if($address)
                    <div>
                        <h2>Find us</h2>
                        <p>{{ $address }}</p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener">Get directions</a>
                    </div>
                @endif

                @if($hours)
                    <div>
                        <h2>Opening hours</h2>
                        <dl>
                            @foreach($hours as $days => $time)
                                <dt>{{ $days }}</dt>
                                <dd>{{ $time }}</dd>
                            @endforeach
                        </dl>
                    </div>
                @endif
            </div>
        @endif

    </div>
</section>

<section class="ct-section ct-section--last">
    <div class="ct-wrap">
        <div class="ct-faq">
            <h2 class="ct-reveal">Quick answers</h2>

            <details class="ct-reveal">
                <summary>Do you deliver?</summary>
                <p>Not at the moment. Orders are collection only, so choose a pickup time at checkout and collect your order from the cafe.</p>
            </details>

            <details class="ct-reveal">
                <summary>How do I order?</summary>
                <p>Create an account, add what you would like to your cart, choose a pickup time and pay online. Your order then shows under My Orders.</p>
            </details>

            <details class="ct-reveal">
                <summary>When are the runs, and do I need to RSVP?</summary>
                <p>The club runs on Saturdays from the cafe. Log in and RSVP on the Running Club page. RSVPs close 8 hours before the event starts, so we know how many people to expect.</p>
            </details>

            <details class="ct-reveal">
                <summary>Where can I get run club news?</summary>
                <p>Join our WhatsApp channel using the button above. That is where updates about the runs are shared.</p>
            </details>
        </div>
    </div>
</section>

<script>
(function () {
    var heads   = document.querySelectorAll('.ct-head');
    var reveals = document.querySelectorAll('.ct-reveal');

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
        // Stagger neighbours, such as the three contact cards
        var index = Array.prototype.indexOf.call(el.parentElement.children, el);
        el.style.setProperty('--d', (Math.min(index, 4) * 0.1) + 's');
        observer.observe(el);
    });
})();
</script>
@endsection