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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Sign-in tokens ── */
        .auth {
            --auth-plum:   #632E62;
            --auth-pink:   #FCBDE8;
            --auth-lime:   #B8F56A;
            --auth-ink:    #17131B;
            --auth-muted:  #5F5A66;
            --auth-line:   #E5DDE8;

            /* Flux reads these for primary buttons and links */
            --color-accent: var(--auth-ink);
            --color-accent-content: var(--auth-plum);
            --color-accent-foreground: #FFFFFF;

            font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--auth-ink);
            background: #FFFFFF;
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

        /* ── Brand panel ── */
        .auth-brand {
            background: var(--auth-plum);
            color: #FFFFFF;
            padding: 1.5rem 1.25rem;
        }

        .auth-brand-inner {
            max-width: 26rem;
            margin: 0 auto;
        }

        .auth-wordmark {
            display: inline-flex;
            gap: 0.4rem;
            text-decoration: none;
        }

        .auth-wordmark span {
            padding: 0.2rem 0.6rem;
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--auth-ink);
        }

        .auth-wordmark .live { background: var(--auth-pink); letter-spacing: 0.12em; }
        .auth-wordmark .cafe { background: var(--auth-lime); }

        .auth-tagline,
        .auth-photo,
        .auth-badge { display: none; }

        @media (min-width: 1024px) {
            .auth-brand {
                display: flex;
                align-items: center;
                padding: 4rem 5rem;
            }

            .auth-brand-inner { margin: 0; max-width: 32rem; }

            .auth-tagline { display: block; margin-top: 2.5rem; }
            .auth-photo { display: block; }
            .auth-badge { display: inline-block; }
        }

        .auth-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--auth-lime);
        }

        .auth-headline {
            margin-top: 0.75rem;
            font-size: clamp(2rem, 3vw, 2.4rem);
            font-weight: 800;
            line-height: 1.15;
        }

        .auth-subline {
            margin-top: 0.75rem;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--auth-pink);
        }

        .auth-photo {
            position: relative;
            margin-top: 2.5rem;
            width: min(100%, 21.5rem);
        }

        /* Lime block peeking out behind the photo */
        .auth-photo::before {
            content: '';
            position: absolute;
            inset: 0.75rem -0.75rem -0.75rem 0.75rem;
            background: var(--auth-lime);
        }

        .auth-photo img {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 6 / 5;
            object-fit: cover;
        }

        .auth-badge {
            margin-top: 2.5rem;
            padding: 0.15rem 0.5rem;
            background: var(--auth-lime);
            color: var(--auth-ink);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* ── Form side ── */
        .auth-main {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1.25rem 3rem;
        }

        @media (min-width: 1024px) {
            .auth-main {
                align-items: center;
                padding: 4rem 5rem;
            }
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 26rem;
        }

        .auth-back {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--auth-line);
            font-size: 0.875rem;
        }

        /* ── Flux components, squared off to match the brand ── */
        .auth [data-flux-label] {
            color: var(--auth-ink);
            font-weight: 500;
        }

        .auth input[data-flux-control] {
            border-radius: 0;
            border-color: var(--auth-line);
            box-shadow: none;
            height: 2.75rem;
            background: #FFFFFF;
        }

        .auth input[data-flux-control][data-invalid] {
            border-color: #DC2626;
        }

        .auth input:focus[data-flux-control] {
            --tw-ring-color: transparent;
            --tw-ring-offset-width: 0;
            outline: 2px solid var(--auth-plum);
            outline-offset: -2px;
            border-color: var(--auth-plum);
        }

        .auth [data-flux-button][type="submit"] {
            border-radius: 0;
            height: 2.75rem;
            font-weight: 700;
        }

        .auth [data-flux-checkbox] [data-flux-checkbox-indicator] {
            border-radius: 0;
        }

        .auth [data-flux-link] {
            color: var(--auth-plum);
            text-decoration: none;
        }

        .auth [data-flux-link]:hover { text-decoration: underline; }
    </style>
</head>
<body class="antialiased">
    <div class="auth">
        <aside class="auth-brand">
            <div class="auth-brand-inner">
                <a href="{{ route('home') }}" class="auth-wordmark" aria-label="Live Cafe home">
                    <span class="live">LIVE</span>
                    <span class="cafe">café</span>
                </a>

                <div class="auth-tagline">
                    <p class="auth-eyebrow">Eat · Drink · Live</p>
                    <p class="auth-headline">Café. Culture.<br>Community.</p>
                    <p class="auth-subline">Where wellness meets flavour</p>
                </div>

                <div class="auth-photo">
                    <img src="{{ asset('images/running/club-group.jpg') }}" alt="Live Running Club members together after a run">
                </div>

                <span class="auth-badge">Collection only</span>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-form-wrap">
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
