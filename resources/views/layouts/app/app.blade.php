<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Live Cafe')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Tokens ── */
        :root {
            /* Assigned Pantone Palettes */
            --green:      #B598A3; /* Main: Pantone TCX Mauve Shadows */
            --accent:     #86A96F; /* Secondary: Pantone TCX Matcha Green */
            --base:       #F0EEE9; /* Third: Pantone TCX White Cloud */
            
            /* Retained original UI elements */
            --green-light:#9D7E8B; /* Darker/Muted Mauve variation for hover/borders */
            --sage:       #DCD3D7; /* Tinted Mauve split */
            --slate:      #4A5568;
            --ink:        #1A1A1A;
            --white:      #FFFFFF;
            --danger:     #C0392B;
            --success:    #27AE60;

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

        .nav-logo {
            font-family: var(--font-display);
            font-size: 1.4rem;
            color: #000000;
            text-decoration: none;
            letter-spacing: -0.01em;
        }

        .nav-logo span { color: #FFFFFF }

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
        .main { flex: 1; }

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

        .footer-logo {
            font-family: var(--font-display);
            font-size: 1.1rem;
            color: #000000;
        }

        .footer-logo span { color: #FFFFFF; }

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

    {{-- Navigation --}}
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-logo">Live<span>Cafe</span></a>

            <ul class="nav-links">
                <li><a href="{{ route('shop.index') }}"
                    class="{{ request()->is('shop*') ? 'active' : '' }}">Shop</a></li>

                <li><a href="{{ route('running.index') }}"
                    class="{{ request()->is('running*') ? 'active' : '' }}">Running Club</a></li>

                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->is('admin*') ? 'active' : '' }}">Admin</a></li>
                    @endif

                    @if(auth()->user()->isStaffOrAdmin())
                        <li><a href="{{ route('pos.index') }}"
                            class="{{ request()->is('pos*') ? 'active' : '' }}">POS</a></li>
                    @endif

                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.9);font-size:0.9rem;font-weight:500;font-family:var(--font-body);">
                                Sign out
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Sign in</a></li>
                    <li><a href="{{ route('register') }}" class="nav-btn">Join</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session()->hasAny(['success', 'error', 'info']))
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

    {{-- Page content --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-logo">Live<span>Cafe</span></span>

            <ul class="footer-links">
                <li><a href="{{ route('shop.index') }}">Shop</a></li>
                <li><a href="{{ route('running.index') }}">Running Club</a></li>
                <li><a href="{{ route('running.announcements.index') }}">Announcements</a></li>
            </ul>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
