{{--
    Run club styles. Everything uses the site's tokens from layouts/app.blade.php
    (--green is the mauve, --accent the matcha), and every class starts with
    "rc-" so nothing here can clash with another module's page styles.
--}}
<style>
    .rc-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .rc-eyebrow {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--slate);
    }

    .rc-muted { color: var(--slate); font-size: 0.85rem; }
    .rc-muted a, .rc-link, .rc-footnote a, .rc-prose a, .rc-facts a {
        color: var(--ink);
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
    }
    .rc-muted a:hover, .rc-link:hover, .rc-footnote a:hover, .rc-prose a:hover, .rc-facts a:hover { color: var(--green-light); }

    .rc-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* ── Hero: white card on the mauve band, group photo alongside ── */
    .rc-hero {
        background: var(--green);
        margin-top: -3rem; /* sit flush under the nav (the layout pads .main) */
        padding: 2.5rem 0;
    }

    .rc-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 2.5rem;
        align-items: center;
    }

    .rc-hero-card {
        background: var(--white);
        padding: 2.25rem 2rem;
    }

    .rc-hero-title {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 2.6rem);
        color: var(--ink);
        line-height: 1.1;
        margin: 0.6rem 0 0.9rem;
    }

    .rc-hero-lead {
        color: var(--slate);
        line-height: 1.65;
        max-width: 56ch;
    }

    .rc-hero-photo {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }

    /* ── Page body ── */
    .rc-body { padding-top: 2.5rem; }

    .rc-card {
        background: var(--white);
        border: 1px solid var(--sage);
        padding: 1.5rem 1.25rem;
    }

    .rc-announcement { margin-bottom: 2.5rem; }

    .rc-announcement-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .rc-announcement-title {
        font-family: var(--font-display);
        font-size: 1.25rem;
        color: var(--ink);
        margin-bottom: 0.4rem;
    }

    .rc-announcement-body {
        color: var(--slate);
        line-height: 1.6;
        margin-bottom: 0.9rem;
        white-space: pre-line;
    }

    .rc-section-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 1rem;
        padding-bottom: 0.9rem;
        border-bottom: 1px solid var(--sage);
        flex-wrap: wrap;
    }

    .rc-section-title {
        font-family: var(--font-display);
        font-size: 1.5rem;
        color: var(--ink);
    }

    .rc-count { color: var(--slate); font-family: var(--font-body); font-size: 1rem; font-weight: 500; }

    /* ── Badges ── */
    .rc-badge {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.25rem 0.55rem;
        border-radius: 3px;
        white-space: nowrap;
        line-height: 1.3;
    }

    .rc-badge-open, .rc-badge-pinned { background: var(--accent); color: var(--ink); }
    .rc-badge-going { background: #eef5e9; color: #2f4d20; border: 1px solid var(--accent); }
    .rc-badge-closed { background: var(--sage); color: var(--slate); }
    .rc-badge-cancelled { background: #fdf0ef; color: #7b1c14; border: 1px solid #f1c4bf; }
    .rc-badge-type { background: var(--base); color: var(--ink); border: 1px solid var(--sage); }
    .rc-badge-sponsor { background: var(--ink); color: var(--white); }
    .rc-badge-draft { background: #fff8e6; color: #6b4d00; border: 1px solid #f0dca4; }

    /* ── Run rows ── */
    .rc-row {
        display: grid;
        grid-template-columns: 64px minmax(0, 1fr) auto;
        gap: 1.25rem;
        align-items: center;
        padding: 1.25rem 0;
        border-bottom: 1px solid var(--sage);
    }

    .rc-row.is-cancelled .rc-row-title a { text-decoration: line-through; text-decoration-color: var(--slate); }

    .rc-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--white);
        border: 1px solid var(--sage);
        text-align: center;
        line-height: 1.1;
        padding-bottom: 0.4rem;
    }

    .rc-date-day {
        align-self: stretch;
        background: var(--green);
        color: var(--ink);
        font-size: 0.66rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 0.25rem 0;
    }

    .rc-date-num {
        font-family: var(--font-display);
        font-size: 1.45rem;
        color: var(--ink);
        margin-top: 0.3rem;
    }

    .rc-date-month { font-size: 0.66rem; color: var(--slate); letter-spacing: 0.06em; }

    .rc-row-title {
        font-family: var(--font-display);
        font-size: 1.15rem;
        margin-bottom: 0.3rem;
    }

    .rc-row-title a { color: var(--ink); text-decoration: none; }
    .rc-row-title a:hover { color: var(--green-light); }

    .rc-row-meta { color: var(--slate); font-size: 0.9rem; }

    .rc-row-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.5rem; }

    .rc-row-side {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .rc-closes { font-size: 0.8rem; color: var(--slate); white-space: nowrap; }

    .rc-empty {
        padding: 2.5rem 0;
        color: var(--slate);
        border-bottom: 1px solid var(--sage);
    }

    .rc-footnote { margin-top: 1.75rem; color: var(--slate); font-size: 0.9rem; }

    /* ── Buttons ── */
    .rc-btn {
        display: inline-block;
        font-family: var(--font-body);
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.55rem 1.2rem;
        border-radius: 3px;
        text-decoration: none;
        cursor: pointer;
        text-align: center;
        border: 1.5px solid transparent;
        transition: opacity 0.15s, background 0.15s, color 0.15s;
        line-height: 1.3;
    }

    .rc-btn-primary { background: var(--accent); color: var(--ink); }
    .rc-btn-primary:hover { opacity: 0.88; }
    .rc-btn-outline { background: var(--white); color: var(--ink); border-color: var(--ink); }
    .rc-btn-outline:hover { background: var(--ink); color: var(--white); }
    .rc-btn-block { display: block; width: 100%; }
    .rc-btn-small { font-size: 0.8rem; padding: 0.35rem 0.75rem; }

    .rc-btn-text {
        background: none;
        border: none;
        padding: 0;
        font-family: var(--font-body);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ink);
        cursor: pointer;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .rc-btn-text.is-danger { color: var(--danger); }

    /* ── Run details page ── */
    .rc-detail { padding-top: 0.5rem; }

    .rc-back {
        display: inline-block;
        color: var(--slate);
        font-size: 0.9rem;
        text-decoration: none;
        margin-bottom: 1.25rem;
    }

    .rc-back:hover { color: var(--ink); }

    .rc-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 2.5rem;
        align-items: start;
    }

    .rc-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.75rem; }

    .rc-detail-title {
        font-family: var(--font-display);
        font-size: clamp(1.9rem, 4vw, 2.6rem);
        color: var(--ink);
        line-height: 1.15;
    }

    .rc-detail-when { color: var(--slate); margin: 0.4rem 0 1.5rem; }

    .rc-detail-photo {
        display: block;
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        margin-bottom: 1.75rem;
        background: var(--sage);
    }

    .rc-facts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1px;
        background: var(--sage);
        border: 1px solid var(--sage);
        margin-bottom: 2rem;
    }

    .rc-facts > div { background: var(--white); padding: 0.9rem 1rem; }
    .rc-facts dt { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--slate); margin-bottom: 0.25rem; }
    .rc-facts dd { color: var(--ink); line-height: 1.5; }
    .rc-facts .rc-fact-wide { grid-column: 1 / -1; }
    .rc-map-link { font-size: 0.85rem; }

    .rc-subtitle {
        font-family: var(--font-display);
        font-size: 1.3rem;
        color: var(--ink);
        margin-bottom: 0.6rem;
    }

    .rc-prose { color: var(--slate); line-height: 1.7; }

    /* ── RSVP card ── */
    .rc-rsvp { position: sticky; top: calc(var(--nav-h) + 1.5rem); }

    .rc-rsvp-title {
        font-family: var(--font-display);
        font-size: 1.35rem;
        color: var(--ink);
        margin-bottom: 0.6rem;
    }

    .rc-rsvp p { color: var(--slate); line-height: 1.55; margin-bottom: 1rem; font-size: 0.92rem; }
    .rc-rsvp .rc-going { color: #2f4d20; font-weight: 600; font-size: 1rem; }
    .rc-rsvp form + form { margin-top: 1rem; }

    .rc-rsvp .rc-rsvp-alt { margin: 0.9rem 0 0; }

    .rc-label { display: block; font-weight: 600; font-size: 0.9rem; color: var(--ink); margin-bottom: 0.2rem; }
    .rc-hint { display: block; font-size: 0.8rem; color: var(--slate); margin-bottom: 0.6rem; }

    .rc-stepper {
        display: inline-flex;
        align-items: stretch;
        border: 1.5px solid var(--ink);
        border-radius: 3px;
        margin-bottom: 1.1rem;
        background: var(--white);
    }

    .rc-stepper button {
        width: 2.75rem;
        background: var(--white);
        border: none;
        font-size: 1.25rem;
        line-height: 1;
        cursor: pointer;
        color: var(--ink);
    }

    .rc-stepper button:hover:not(:disabled) { background: var(--base); }
    .rc-stepper button:disabled { color: #bbb; cursor: not-allowed; }

    .rc-stepper input {
        width: 3.5rem;
        border: none;
        border-left: 1px solid var(--sage);
        border-right: 1px solid var(--sage);
        text-align: center;
        font-family: var(--font-body);
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--ink);
        padding: 0.5rem 0;
        -moz-appearance: textfield;
        appearance: textfield;
    }

    .rc-stepper input::-webkit-outer-spin-button,
    .rc-stepper input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Validation messages. ".rc-rsvp .rc-error" has to beat ".rc-rsvp p" so
       the message stays red inside the RSVP card. */
    .rc-error { color: var(--danger); font-size: 0.85rem; margin-top: 0.35rem; }
    .rc-rsvp .rc-error { color: var(--danger); font-size: 0.85rem; margin: -0.5rem 0 1rem; }

    /* ── Simple page header (announcements, my RSVPs) ── */
    .rc-page-head { padding: 0.5rem 0 1.25rem; border-bottom: 1px solid var(--sage); margin-bottom: 0.5rem; }
    .rc-page-title { font-family: var(--font-display); font-size: clamp(1.8rem, 3.5vw, 2.3rem); color: var(--ink); margin-top: 0.4rem; }
    .rc-page-lead { color: var(--slate); margin-top: 0.4rem; }
    .rc-page-head + .rc-admin-bar { margin-top: 1.5rem; }

    .rc-list-card { padding: 1.4rem 0; border-bottom: 1px solid var(--sage); }
    .rc-list-card h2 { font-family: var(--font-display); font-size: 1.2rem; margin-bottom: 0.35rem; }
    .rc-list-card h2 a { color: var(--ink); text-decoration: none; }
    .rc-list-card h2 a:hover { color: var(--green-light); }
    .rc-list-card .rc-announcement-body { margin-bottom: 0.5rem; }

    .rc-article-body { margin-top: 1.25rem; }

    .rc-announcement-link { margin: -0.25rem 0 0.9rem; }
    .rc-article-body + .rc-announcement-link { margin-top: 1.5rem; }

    /* ── Admin shortcuts (only admins see these) ── */
    .rc-admin-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem 1.5rem;
        flex-wrap: wrap;
        background: var(--white);
        border: 1px dashed var(--green);
        padding: 0.85rem 1.1rem;
        margin-bottom: 2rem;
    }

    .rc-admin-bar-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--slate);
    }

    .rc-admin-bar-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem 1rem;
        flex-wrap: wrap;
    }

    .rc-admin-bar .rc-link { font-size: 0.85rem; }

    @media (max-width: 860px) {
        .rc-hero-inner { grid-template-columns: 1fr; gap: 1.5rem; }
        .rc-hero-photo { height: 220px; }
        .rc-rsvp { position: static; }

        /* On one column, lift the RSVP box up to sit under the date instead of
           below the description. "display: contents" lets the main column's
           children join the grid so "order" can slot the box between them. */
        .rc-detail-grid { grid-template-columns: 1fr; gap: 0; }
        .rc-detail-main { display: contents; }
        .rc-detail-side { order: 1; margin-bottom: 1.75rem; }
        .rc-detail-photo, .rc-facts, .rc-subtitle, .rc-prose { order: 2; }
    }

    @media (max-width: 600px) {
        .rc-wrap { padding: 0 1rem; }
        .rc-hero { padding: 1.5rem 0; }
        .rc-hero-card { padding: 1.5rem 1.25rem; }
        .rc-row { grid-template-columns: 56px minmax(0, 1fr); gap: 0.9rem; }
        .rc-row-side { grid-column: 1 / -1; justify-content: flex-start; }
        .rc-facts { grid-template-columns: 1fr; }
    }
</style>
