<style>
    .admin-wrap {
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

    .admin-sidebar-section {
        margin-bottom: 1.75rem;
    }

    .admin-sidebar-label {
        font-size: 0.65rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
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
        color: rgba(255,255,255,0.72);
        text-decoration: none;
        transition: color 0.12s, background 0.12s;
        border-left: 3px solid transparent;
    }

    .admin-sidebar a:hover,
    .admin-sidebar a.active {
        color: var(--white);
        background: rgba(255,255,255,0.07);
        border-left-color: var(--accent);
    }

    /* ── Main area ── */
    .admin-main { padding: 2.5rem 2rem; }

    .admin-main h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--green);
        margin-bottom: 0.25rem;
    }

    .admin-main .admin-sub {
        font-size: 0.875rem;
        color: var(--slate);
        margin-bottom: 2.5rem;
    }

    /* ── Stat cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1px;
        background: #ddd;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: var(--white);
        padding: 1.5rem;
    }

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
        color: var(--green);
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .stat-card-note {
        font-size: 0.75rem;
        color: #aaa;
    }

    /* ── Tables ── */
    .admin-section { margin-bottom: 3rem; }

    .admin-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .admin-section-header h2 {
        font-family: var(--font-display);
        font-size: 1.15rem;
        color: var(--green);
    }

    .admin-section-header a {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 1px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: var(--white);
    }

    .data-table th {
        background: var(--green);
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
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: var(--base); }

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

    @media (max-width: 768px) {
        .admin-wrap { grid-template-columns: 1fr; }
        .admin-sidebar { min-height: auto; position: static; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>