{{-- resources/views/admin/partials/styles.blade.php --}}
<style>
    .main { padding: 0; }   /* admin pages only: the sidebar starts right under the nav bar */
    .footer { display: none; }

    /* ── Admin colours ──
       --admin-ink is a darker shade of the brand mauve (--green).
       White text on it passes WCAG AA (6.9:1); on --green it only reaches 2.6:1. */
    .admin-wrap {
        --admin-ink: #6E5260;
        --admin-line: #E4DDE0;

        display: grid;
        grid-template-columns: 220px 1fr;
        min-height: calc(100vh - var(--nav-h));
        align-items: start;
    }

    /* ── Sidebar ── */
    .admin-sidebar {
        background: var(--green);
        min-height: calc(100vh - var(--nav-h));
        padding: 2rem 0;
        position: sticky;
        top: var(--nav-h);
    }

    .admin-sidebar-section { margin-bottom: 1.75rem; }

    .admin-sidebar-label {
        font-size: 0.65rem;
        font-weight: 600;
        color: rgba(255,255,255,0.75);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0 1.25rem;
        margin-bottom: 0.4rem;
    }

    .admin-sidebar ul { list-style: none; }

    .admin-sidebar a {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 1.25rem;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: color 0.12s, background 0.12s;
        border-left: 3px solid transparent;
    }

    .admin-sidebar a:hover,
    .admin-sidebar a.active {
        color: var(--white);
        background: rgba(255,255,255,0.1);
        border-left-color: var(--accent);
    }

    .admin-sidebar a.active { font-weight: 600; }

    /* ── Main area ── */
    .admin-main { padding: 2.5rem 2rem; min-width: 0; }   /* min-width: 0 lets wide tables scroll instead of stretching the page */

    .admin-main h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--admin-ink);
        margin-bottom: 0.25rem;
    }

    .admin-main h2 { font-family: var(--font-display); color: var(--admin-ink); }

    .admin-main .admin-sub {
        font-size: 0.875rem;
        color: var(--slate);
        margin-bottom: 2rem;
    }

    .admin-main a { color: var(--admin-ink); font-weight: 600; }
    .admin-main .hint { font-size: 0.8rem; color: var(--slate); font-weight: 400; }

    /* ── Messages (form errors) ── */
    .alert { padding: 0.85rem 1rem; border-left: 4px solid; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.875rem; }
    .alert ul { margin: 0; padding-left: 1.1rem; }
    .alert-error   { border-color: var(--danger);  background: #fdf0ef; color: #7b1c14; }
    .alert-success { border-color: var(--success); background: #edfaf3; color: #1a5c38; }
    .field-error   { color: var(--danger); font-size: 0.78rem; font-weight: 500; }

    /* ── Stat cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1px;
        background: var(--admin-line);
        margin-bottom: 3rem;
    }

    .stat-card { background: var(--white); padding: 1.5rem; }

    .stat-card-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.6rem;
    }

    .stat-card-value {
        font-family: var(--font-display);
        font-size: 2rem;
        color: var(--admin-ink);
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .stat-card-value.danger { color: #b42318; }   /* open sync conflicts, switched on by the dashboard's polling script */
    .stat-card-note { font-size: 0.75rem; color: var(--slate); }   /* was #aaa: too faint to read (2.3:1) */

    /* ── Sections and tables ── */
    .admin-section { margin-bottom: 3rem; }

    .admin-section-header {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .admin-section-header h2 { font-size: 1.15rem; }

    .admin-section-header a:not(.btn) {
        font-size: 0.8rem;
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 1px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: var(--white);
        margin-bottom: 1rem;
    }

    .data-table th {
        background: var(--admin-ink);
        color: var(--white);
        padding: 0.7rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 0.02em;
    }

    .data-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        color: var(--slate);
        vertical-align: middle;
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: var(--base); }
    .data-table td.empty { padding: 1.5rem 1rem; text-align: center; }

    .status-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 3px;
        text-transform: capitalize;
    }

    .status-pending   { background: #fff8e6; color: #8a6200; }
    .status-confirmed { background: #e8f5e9; color: #2e7d32; }
    .status-collected { background: #e3f2fd; color: #1565c0; }
    .status-cancelled { background: #fce4e4; color: #b71c1c; }

    /* ── Forms ── */
    .admin-form { display: grid; gap: 1rem; max-width: 34rem; margin-bottom: 2.5rem; }
    .admin-form label { display: grid; gap: 0.35rem; font-size: 0.8rem; font-weight: 600; color: var(--ink); }
    .admin-form label.checkbox { display: flex; align-items: center; gap: 0.5rem; font-weight: 500; }
    .admin-form fieldset { border: 1px solid var(--admin-line); border-radius: 6px; padding: 1rem; display: grid; gap: 0.75rem; }
    .admin-form legend { font-weight: 600; font-size: 0.8rem; padding: 0 0.4rem; }
    .admin-form button,
    .admin-form .btn { justify-self: start; }   /* buttons keep their own width instead of filling the row */

    .admin-main input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
    .admin-main select,
    .admin-main textarea {
        font: inherit;
        font-size: 0.875rem;
        font-weight: 400;   /* labels are bold; the text you type shouldn't be */
        color: var(--ink);
        padding: 0.5rem 0.65rem;
        border: 1px solid #8E7C84;   /* 3.9:1 on white, so the box edge is visible */
        border-radius: 6px;
        background: var(--white);
    }

    .admin-main textarea { resize: vertical; }

    .adjust-form,
    .filter-bar,
    .recipe-row { display: flex; flex-wrap: wrap; align-items: end; gap: 0.5rem; }

    .filter-bar { margin-bottom: 1.5rem; }
    .filter-bar label { display: grid; gap: 0.25rem; font-size: 0.75rem; font-weight: 600; color: var(--slate); }
    .adjust-form input[type="number"] { width: 7rem; }

    /* ── Buttons ── */
    .admin-main button,
    .admin-main .btn {
        display: inline-block;
        font: inherit;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.5rem 0.9rem;
        border: 1px solid var(--admin-ink);
        border-radius: 6px;
        background: var(--admin-ink);
        color: var(--white);
        text-decoration: none;
        cursor: pointer;
    }

    .admin-main button:hover,
    .admin-main .btn:hover { opacity: 0.88; }

    .admin-main button:disabled { opacity: 0.5; cursor: wait; }

    .admin-main .btn-quiet  { background: var(--white); color: var(--admin-ink); }
    .admin-main .btn-danger,
    .admin-main .recipe-remove { background: var(--white); color: var(--danger); border-color: var(--danger); }
    /* ── "New …" forms that fold open (Announcements) ── */
    .new-item { margin-bottom: 2rem; }
    .new-item > summary { list-style: none; }                        /* hides the ▶ triangle */
    .new-item > summary::-webkit-details-marker { display: none; }   /* same, for Safari */
    .new-item[open] > summary { margin-bottom: 1.25rem; }

    /* ── Keyboard focus ── */
    .admin-wrap a:focus-visible,
    .admin-wrap button:focus-visible,
    .admin-wrap input:focus-visible,
    .admin-wrap select:focus-visible,
    .admin-wrap textarea:focus-visible {
        outline: 3px solid var(--admin-ink);
        outline-offset: 2px;
    }

    .admin-sidebar a:focus-visible { outline-color: var(--white); outline-offset: -3px; }

    /* ── Page numbers (Laravel's 'pagination::bootstrap-4' view) ── */
    .pagination { display: flex; flex-wrap: wrap; gap: 0.25rem; list-style: none; margin: 1rem 0 0; padding: 0; }
    .page-link {
        display: block;
        padding: 0.35rem 0.7rem;
        border: 1px solid var(--admin-line);
        border-radius: 4px;
        background: var(--white);
        text-decoration: none;
    }
    .page-item.active .page-link { background: var(--admin-ink); border-color: var(--admin-ink); color: var(--white); }
    .page-item.disabled .page-link { color: #767676; }

    /* ── Phones and tablets ── */
    @media (max-width: 768px) {
        .admin-wrap { grid-template-columns: 1fr; align-content: start; }   /* align-content stops a gap opening under the menu */

        /* the sidebar turns into one row of links that scrolls sideways */
        .admin-sidebar { display: flex; min-height: auto; padding: 0; overflow-x: auto; z-index: 50; }
        .admin-sidebar-section { margin-bottom: 0; }
        .admin-sidebar-label { display: none; }
        .admin-sidebar ul { display: flex; }
        .admin-sidebar a { white-space: nowrap; padding: 0.75rem 1rem; border-left: 0; border-bottom: 3px solid transparent; }
        .admin-sidebar a:hover,
        .admin-sidebar a.active { border-bottom-color: var(--accent); }

        .admin-main { padding: 1.5rem 1rem; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .admin-main table { display: block; overflow-x: auto; white-space: nowrap; }   /* wide tables scroll on their own */
        .admin-main table .adjust-form { flex-wrap: nowrap; }   /* forms in a table row stay on one line and scroll with it */
    }

    @media (max-width: 480px) {
        .stat-grid { grid-template-columns: 1fr; }
    }
</style>