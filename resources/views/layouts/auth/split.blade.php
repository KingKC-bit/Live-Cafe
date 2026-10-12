<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Same as partials.head, minus @fluxAppearance: the sign-in pages are always light, like the public site. --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/livecafelogo.jpeg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Tokens: the same values as layouts/app.blade.php, so these pages match the rest of the site ── */
        .auth {
            --green:       #B598A3; /* mauve */
            --accent:      #86A96F; /* matcha */
            --base:        #F0EEE9;
            --green-light: #9D7E8B;
            --sage:        #DCD3D7;
            --slate:       #4A5568;
            --ink:         #1A1A1A;
            --white:       #FFFFFF;
            --danger:      #C0392B;
            --success:     #27AE60;

            --font-body:    'DM Sans', sans-serif;
            --font-display: 'Playfair Display', serif;

            /* Flux reads these for its primary button, tick box and links */
            --color-accent: var(--accent);
            --color-accent-content: var(--ink);
            --color-accent-foreground: var(--ink);

            font-family: var(--font-body);
            color: var(--ink);
            background: var(--base);
            min-height: 100svh;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        @media (min-width: 1024px) {
            .auth {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: none;
            }
        }

        /* ── Brand panel: the mauve of the nav and home page hero ── */
        .auth-brand {
            background: var(--green);
            color: var(--white);
            padding: 0.75rem 1.25rem;
            border-bottom: 2px solid var(--accent);
        }

        .auth-brand-inner {
            max-width: 26rem;
            margin: 0 auto;
        }

        .auth-logo img {
            display: block;
            height: 44px;
            width: auto;
            object-fit: contain;
        }

        .auth-tagline,
        .auth-photo,
        .auth-badge { display: none; }

        @media (min-width: 1024px) {
            .auth-brand {
                display: flex;
                align-items: center;
                padding: 4rem 5rem;
                border-bottom: none;
                border-right: 2px solid var(--accent);
            }

            .auth-brand-inner { margin: 0; max-width: 32rem; }

            .auth-tagline { display: block; margin-top: 2.5rem; }
            .auth-photo { display: block; }
            .auth-badge { display: inline-block; }
        }

        /* Same as the home page hero's label */
        .auth-eyebrow {
            display: inline-block;
            background: var(--accent);
            color: var(--ink);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            padding: 0.3rem 0.75rem;
        }

        .auth-headline {
            margin-top: 1.25rem;
            font-family: var(--font-display);
            font-size: clamp(2.2rem, 3.4vw, 2.9rem);
            font-weight: 700;
            line-height: 1.1;
        }

        .auth-subline {
            margin-top: 0.9rem;
            font-size: 1.05rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        .auth-photo {
            position: relative;
            margin-top: 2.5rem;
            width: min(100%, 21.5rem);
        }

        /* Matcha block peeking out behind the photo */
        .auth-photo::before {
            content: '';
            position: absolute;
            inset: 0.75rem -0.75rem -0.75rem 0.75rem;
            background: var(--accent);
        }

        .auth-photo img {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 6 / 5;
            object-fit: cover;
        }

        /* Same as the run club's badges */
        .auth-badge {
            margin-top: 2.5rem;
            padding: 0.25rem 0.55rem;
            border-radius: 3px;
            background: var(--accent);
            color: var(--ink);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── Form side: a white card on the cream page, like the run club cards ── */
        .auth-main {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem 3rem;
        }

        @media (min-width: 1024px) {
            .auth-main {
                align-items: center;
                padding: 4rem 5rem;
            }
        }

        .auth-card {
            width: 100%;
            max-width: 28rem;
            background: var(--white);
            border: 1px solid var(--sage);
            padding: 2rem 1.25rem;
        }

        @media (min-width: 640px) {
            .auth-card { padding: 2.5rem 2.25rem; }
        }

        .auth-kicker {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--slate);
        }

        .auth-title {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.15;
            color: var(--ink);
        }

        .auth-lead,
        .auth-note {
            color: var(--slate);
            font-size: 0.9rem;
        }

        /* Same as the site's green message box (.flash-success in layouts/app) */
        .auth-status {
            padding: 0.85rem 1.2rem;
            border-left: 4px solid var(--success);
            background: #edfaf3;
            color: #1a5c38;
            font-size: 0.9rem;
            font-weight: 400;
        }

        .auth-back {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--sage);
            font-size: 0.875rem;
        }

        /* ── Flux components, styled like the site's own forms ── */
        .auth [data-flux-label] {
            color: var(--ink);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .auth input[data-flux-control] {
            height: 2.75rem;
            border: 1.5px solid var(--sage);
            border-radius: 3px;
            box-shadow: none;
            background: var(--white);
            color: var(--ink);
            font-family: var(--font-body);
        }

        .auth input:focus[data-flux-control] {
            --tw-ring-color: transparent;
            --tw-ring-offset-width: 0;
            outline: 2px solid var(--green);
            outline-offset: 1px;
            border-color: var(--green);
        }

        /* After the focus rule, so a field with an error stays red while you're typing in it */
        .auth input[data-flux-control][data-invalid] {
            border-color: var(--danger);
        }

        .auth [data-flux-error] {
            color: var(--danger);
        }

        .auth [data-flux-button][type="submit"] {
            height: 2.75rem;
            border-radius: 3px;
            font-family: var(--font-body);
            font-weight: 600;
        }

        .auth [data-flux-checkbox] [data-flux-checkbox-indicator] {
            border-radius: 3px;
        }

        .auth [data-flux-link] {
            color: var(--ink);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1.5px solid var(--accent);
        }

        .auth [data-flux-link]:hover { color: var(--green-light); }
    </style>
</head>
<body class="antialiased">
    <div class="auth">
        <aside class="auth-brand">
            <div class="auth-brand-inner">
                <a href="{{ route('home') }}" class="auth-logo">
                    <img src="{{ asset('images/livecafelogo.jpeg') }}" alt="Live Cafe home">
                </a>

                <div class="auth-tagline">
                    <p class="auth-eyebrow">Eat · Drink · Live</p>
                    <p class="auth-headline">Café. Culture.<br>Community.</p>
                    <p class="auth-subline">Where wellness meets flavour</p>
                </div>

                <div class="auth-photo">
                    <img src="{{ asset('images/Coffee.png') }}" alt="Live Running Club members together after a run">
                </div>

                <span class="auth-badge">Collection only</span>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                {{ $slot }}

                <p class="auth-back">
                    <flux:link :href="route('shop.index')">{{ __('Back to the shop') }}</flux:link>
                </p>
            </div>
        </main>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
