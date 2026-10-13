{{-- Extra styles for the admin management pages, on top of the run club styles. --}}
<style>
    .rc-manage-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
        padding: 0.5rem 0 1.25rem;
    }

    .rc-manage-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; }

    .rc-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--sage);
        margin-bottom: 2rem;
    }

    .rc-tab {
        padding: 0.6rem 0.9rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--slate);
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
    }

    .rc-tab:hover { color: var(--ink); }
    .rc-tab.is-active { color: var(--ink); border-bottom-color: var(--green); }
    .rc-tab-public { margin-left: auto; }

    .rc-manage-section { margin-bottom: 2.75rem; }
    .rc-manage-intro { display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; }

    .rc-manage-section h2 {
        font-family: var(--font-display);
        font-size: 1.25rem;
        color: var(--ink);
        margin-bottom: 0.9rem;
    }

    /* Tables scroll sideways inside their box on small screens instead of
       making the whole page scroll. */
    /* position: relative keeps the hidden "Actions" header label inside the
       scroll area. Without it that label sits past the table's right edge
       and makes the whole page scroll sideways on a phone. */
    .rc-table-wrap {
        position: relative;
        overflow-x: auto;
        background: var(--white);
        border: 1px solid var(--sage);
    }

    .rc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        min-width: 720px;
    }

    .rc-table th {
        text-align: left;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--slate);
        background: var(--base);
        padding: 0.7rem 1rem;
        border-bottom: 1px solid var(--sage);
        white-space: nowrap;
    }

    .rc-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #efecef;
        color: var(--ink);
        vertical-align: top;
    }

    .rc-table tr:last-child td { border-bottom: none; }
    .rc-table td.is-number { font-variant-numeric: tabular-nums; white-space: nowrap; }
    .rc-table .rc-sub { display: block; color: var(--slate); font-size: 0.8rem; margin-top: 0.15rem; }

    .rc-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }
    .rc-actions a { color: var(--ink); font-weight: 600; font-size: 0.85rem; text-decoration: underline; text-underline-offset: 3px; }
    .rc-actions form { display: inline; }

    .rc-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1px;
        background: var(--sage);
        border: 1px solid var(--sage);
        margin-bottom: 1.5rem;
    }

    .rc-stat { background: var(--white); padding: 1.1rem 1.25rem; }
    .rc-stat-label { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--slate); }
    .rc-stat-value { font-family: var(--font-display); font-size: 2rem; color: var(--ink); line-height: 1.1; margin-top: 0.3rem; }

    /* ── Forms ── */
    .rc-form { max-width: 760px; }
    .rc-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem 1.5rem; }
    .rc-field { display: flex; flex-direction: column; }
    .rc-field-wide { grid-column: 1 / -1; }
    .rc-field[hidden] { display: none; }
    .rc-field label, .rc-field legend { font-weight: 600; font-size: 0.9rem; color: var(--ink); margin-bottom: 0.35rem; }
    .rc-optional { color: var(--slate); font-weight: 400; }

    .rc-field input[type="text"],
    .rc-field input[type="date"],
    .rc-field input[type="time"],
    .rc-field input[type="number"],
    .rc-field select,
    .rc-field textarea {
        font-family: var(--font-body);
        font-size: 0.95rem;
        padding: 0.6rem 0.75rem;
        border: 1.5px solid var(--sage);
        border-radius: 3px;
        background: var(--white);
        color: var(--ink);
        width: 100%;
    }

    .rc-field input:focus, .rc-field select:focus, .rc-field textarea:focus { outline: 2px solid var(--green); outline-offset: 1px; border-color: var(--green); }
    .rc-field textarea { min-height: 140px; resize: vertical; line-height: 1.55; }

    .rc-field fieldset { border: none; padding: 0; margin: 0; }
    .rc-field .rc-choice { display: inline-flex; align-items: center; gap: 0.45rem; margin: 0 1.5rem 0.35rem 0; font-size: 0.95rem; font-weight: 400; color: var(--ink); }
    .rc-field .rc-hint { margin: 0.35rem 0 0; }
    .rc-field input[type="file"] { font-family: var(--font-body); font-size: 0.9rem; color: var(--ink); }

    .rc-photo-preview { width: 220px; aspect-ratio: 16 / 9; object-fit: cover; display: block; margin-bottom: 0.6rem; border: 1px solid var(--sage); }

    .rc-closing-note {
        max-width: 760px;
        background: #f7faf4;
        border-left: 4px solid var(--accent);
        padding: 0.8rem 1rem;
        font-size: 0.9rem;
        color: #2f4d20;
        margin: 1.5rem 0;
    }

    .rc-form-buttons { display: flex; gap: 0.9rem; align-items: center; flex-wrap: wrap; margin-top: 1.5rem; }

    .rc-error-summary {
        max-width: 760px;
        background: #fdf0ef;
        border-left: 4px solid var(--danger);
        color: #7b1c14;
        padding: 0.8rem 1rem;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 600px) {
        .rc-form-grid { grid-template-columns: 1fr; }
        .rc-tab-public { margin-left: 0; }
    }
</style>
