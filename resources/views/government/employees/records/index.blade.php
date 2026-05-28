{{-- resources/views/government/employment-records/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employment Records')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --bg:          #f4f6f9;
        --surface:     #ffffff;
        --surface2:    #f0f2f5;
        --border:      #e2e6ed;
        --border2:     #d0d6e0;
        --accent:      #2563eb;
        --accent-light:#dbeafe;
        --accent-text: #1d4ed8;
        --green:       #16a34a;
        --green-bg:    #dcfce7;
        --red:         #dc2626;
        --red-bg:      #fee2e2;
        --amber:       #b45309;
        --amber-bg:    #fef3c7;
        --slate:       #64748b;
        --text:        #111827;
        --text-muted:  #6b7280;
        --text-light:  #9ca3af;
        --mono:        'IBM Plex Mono', monospace;
        --sans:        'Inter', sans-serif;
        --radius:      10px;
        --shadow-sm:   0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
        --shadow-md:   0 4px 16px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.05);
        --transition:  180ms cubic-bezier(.4,0,.2,1);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: var(--sans);
        font-size: 14px;
        min-height: 100vh;
    }

    /* ────────────────────────────────
       LAYOUT  — table + drawer side-by-side
    ──────────────────────────────── */
    .er-layout {
        display: flex;
        align-items: flex-start;
        gap: 0;
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 32px;
        transition: gap var(--transition);
    }
    .er-main {
        flex: 1;
        min-width: 0;
        transition: margin-right var(--transition);
    }
    .er-layout.drawer-open .er-main {
        margin-right: 24px;
    }

    /* ────────────────────────────────
       HEADER
    ──────────────────────────────── */
    .er-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .er-header-left h1 {
        font-size: 20px;
        font-weight: 600;
        letter-spacing: -.3px;
        color: var(--text);
    }
    .er-header-left p {
        color: var(--text-muted);
        margin-top: 3px;
        font-size: 13px;
    }
    .badge-count {
        display: inline-flex;
        align-items: center;
        background: var(--accent-light);
        color: var(--accent-text);
        border-radius: 20px;
        padding: 1px 9px;
        font-size: 12px;
        font-family: var(--mono);
        margin-left: 8px;
        font-weight: 500;
    }

    /* ────────────────────────────────
       FILTER BAR
    ──────────────────────────────── */
    .er-filters {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 14px 18px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
        margin-bottom: 16px;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 150px; }
    .filter-group label {
        font-size: 11px;
        font-family: var(--mono);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .6px;
    }
    .filter-group input,
    .filter-group select {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 7px;
        color: var(--text);
        padding: 7px 11px;
        font-family: var(--sans);
        font-size: 13px;
        outline: none;
        transition: border-color var(--transition), box-shadow var(--transition);
        width: 100%;
    }
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }
    .filter-actions { display: flex; gap: 8px; align-items: flex-end; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 7px;
        padding: 7px 15px;
        font-family: var(--sans);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: background var(--transition), box-shadow var(--transition);
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 1px 4px rgba(37,99,235,.25); }
    .btn-primary:hover { background: var(--accent-text); }
    .btn-ghost {
        background: var(--surface);
        border: 1px solid var(--border2);
        color: var(--text-muted);
    }
    .btn-ghost:hover { background: var(--surface2); color: var(--text); }

    /* ────────────────────────────────
       TABLE CARD
    ──────────────────────────────── */
    .er-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .er-table { width: 100%; border-collapse: collapse; }
    .er-table thead tr {
        background: var(--surface2);
        border-bottom: 1px solid var(--border);
    }
    .er-table th {
        padding: 11px 16px;
        text-align: left;
        font-family: var(--mono);
        font-size: 10.5px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: var(--text-muted);
        white-space: nowrap;
    }
    .er-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background var(--transition);
        cursor: pointer;
    }
    .er-table tbody tr:last-child { border-bottom: none; }
    .er-table tbody tr:hover { background: #f8faff; }
    .er-table tbody tr.row-active { background: var(--accent-light) !important; }
    .er-table td {
        padding: 12px 16px;
        vertical-align: middle;
        color: var(--text);
    }

    /* Avatar */
    .avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: var(--accent-light);
        color: var(--accent-text);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
        margin-right: 8px;
    }
    .cell-employee { display: flex; align-items: center; }
    .cell-employee-name { font-weight: 500; line-height: 1.3; }
    .cell-employee-id { font-family: var(--mono); font-size: 11px; color: var(--text-muted); }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 500;
        text-transform: capitalize;
    }
    .status-badge::before {
        content: ''; width: 5px; height: 5px;
        border-radius: 50%; flex-shrink: 0;
    }
    .status-active     { background: var(--green-bg);  color: var(--green);  }
    .status-active::before     { background: var(--green); }
    .status-terminated { background: var(--red-bg);    color: var(--red);    }
    .status-terminated::before { background: var(--red); }
    .status-resigned   { background: var(--amber-bg);  color: var(--amber);  }
    .status-resigned::before   { background: var(--amber); }
    .status-default    { background: var(--surface2);  color: var(--slate);  }
    .status-default::before    { background: var(--slate); }

    .mono { font-family: var(--mono); font-size: 12px; color: var(--text-muted); }

    /* View button */
    .btn-view {
        background: transparent;
        border: 1px solid var(--border2);
        color: var(--text-muted);
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 12px;
        cursor: pointer;
        font-family: var(--sans);
        transition: all var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-view:hover,
    .row-active .btn-view {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent-text);
    }

    /* Empty state */
    .er-empty { padding: 52px 20px; text-align: center; color: var(--text-muted); }
    .er-empty svg { opacity: .25; margin-bottom: 12px; }

    /* Pagination */
    .er-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        border-top: 1px solid var(--border);
        font-size: 13px;
        color: var(--text-muted);
        flex-wrap: wrap;
        gap: 10px;
    }
    .er-pagination .pagination { display: flex; gap: 4px; list-style: none; }
    .er-pagination .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        padding: 0 7px;
        border-radius: 6px;
        background: var(--surface2);
        border: 1px solid var(--border);
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        transition: all var(--transition);
    }
    .er-pagination .page-item.active .page-link,
    .er-pagination .page-item .page-link:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
    .er-pagination .page-item.disabled .page-link { opacity: .4; pointer-events: none; }

    /* ════════════════════════════════
       SIDE DRAWER  (no backdrop)
    ════════════════════════════════ */
    .er-drawer {
        width: 360px;
        flex-shrink: 0;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 80px);
        position: sticky;
        top: 32px;

        /* hidden by default */
        opacity: 0;
        pointer-events: none;
        transform: translateX(16px);
        width: 0;
        overflow: hidden;
        transition:
            opacity 220ms ease,
            transform 220ms cubic-bezier(.4,0,.2,1),
            width 240ms cubic-bezier(.4,0,.2,1);
    }
    .er-layout.drawer-open .er-drawer {
        opacity: 1;
        pointer-events: all;
        transform: translateX(0);
        width: 360px;
        overflow: visible;
    }

    /* Drawer inner wrapper keeps content from squishing during animation */
    .er-drawer-inner {
        width: 360px;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 80px);
    }

    .drawer-header {
        padding: 16px 18px 14px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-shrink: 0;
        background: var(--surface2);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    .drawer-header-info h2 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.3;
    }
    .drawer-header-info p {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
        font-family: var(--mono);
    }
    .drawer-close {
        background: var(--surface);
        border: 1px solid var(--border2);
        color: var(--text-muted);
        border-radius: 6px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all var(--transition);
    }
    .drawer-close:hover { background: var(--red-bg); border-color: var(--red); color: var(--red); }

    .drawer-body {
        padding: 18px;
        overflow-y: auto;
        flex: 1;
    }
    .drawer-body::-webkit-scrollbar { width: 5px; }
    .drawer-body::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

    /* Detail sections */
    .detail-section { margin-bottom: 20px; }
    .detail-section:last-child { margin-bottom: 0; }
    .detail-section-title {
        font-size: 10px;
        font-family: var(--mono);
        text-transform: uppercase;
        letter-spacing: .8px;
        color: var(--accent-text);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 7px;
        font-weight: 500;
    }
    .detail-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .detail-item { display: flex; flex-direction: column; gap: 2px; }
    .detail-item.full { grid-column: 1 / -1; }
    .detail-label {
        font-size: 10.5px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: .5px;
        font-family: var(--mono);
    }
    .detail-value {
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
        word-break: break-word;
    }
    .detail-value.mono { font-family: var(--mono); font-size: 11.5px; color: var(--slate); }
    .detail-value.muted { color: var(--text-light); font-style: italic; font-weight: 400; }

    /* Disputes */
    .dispute-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--red-bg);
        color: var(--red);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .dispute-none { color: var(--text-light); font-size: 13px; font-style: italic; }

    /* Loading */
    .drawer-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        gap: 10px;
        color: var(--text-muted);
        font-size: 13px;
        flex-direction: column;
    }
    .spinner {
        width: 20px; height: 20px;
        border: 2px solid var(--border2);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .er-layout { flex-direction: column; padding: 20px 16px; }
        .er-layout.drawer-open .er-main { margin-right: 0; }
        .er-layout.drawer-open .er-drawer {
            width: 100% !important;
            max-height: none;
            position: static;
        }
        .er-drawer-inner { width: 100%; }
        .er-table th:nth-child(5),
        .er-table td:nth-child(5),
        .er-table th:nth-child(6),
        .er-table td:nth-child(6) { display: none; }
    }
</style>


@section('content')
<div class="er-layout" id="erLayout">

    {{-- ── Main panel ── --}}
    <div class="er-main">

        {{-- Header --}}
        <div class="er-header">
            <div class="er-header-left">
                <h1>
                    Employment Records
                    <span class="badge-count">{{ $records->total() }}</span>
                </h1>
                <p>View and manage all employee employment history</p>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('government.employment-records.index') }}" class="er-filters">
            <div class="filter-group" style="flex:2; min-width:200px;">
                <label>Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, employer, job title, dept…"
                    autocomplete="off"
                >
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Department</label>
                <select name="department">
                    <option value="">All departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" @selected(request('department') === $dept)>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','status','department']))
                    <a href="{{ route('government.employment-records.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="er-card">
            <table class="er-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Employer</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        @php
                            $name     = $record->employee?->full_name ?? '?';
                            $parts    = explode(' ', trim($name));
                            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                            $statusClass = match(strtolower($record->employment_status ?? '')) {
                                'active'     => 'status-active',
                                'terminated' => 'status-terminated',
                                'resigned'   => 'status-resigned',
                                default      => 'status-default',
                            };
                        @endphp
                        <tr id="row-{{ $record->id }}" onclick="openDrawer({{ $record->id }}, this)">
                            <td class="mono">{{ $record->id }}</td>
                            <td>
                                <div class="cell-employee">
                                    <div class="avatar">{{ $initials }}</div>
                                    <div>
                                        <div class="cell-employee-name">{{ $record->employee?->full_name ?? '—' }}</div>
                                        <div class="cell-employee-id">ID #{{ $record->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $record->employer?->company_name ?? '—' }}</td>
                            <td style="font-weight:500;">{{ $record->job_title ?? '—' }}</td>
                            <td class="mono">{{ $record->department ?? '—' }}</td>
                            <td class="mono">
                                {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('M Y') : '—' }}
                                →
                                {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M Y') : 'Present' }}
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($record->employment_status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-view" title="View details" onclick="event.stopPropagation(); openDrawer({{ $record->id }}, document.getElementById('row-{{ $record->id }}'))">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="er-empty">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    <p>No employment records found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($records->hasPages())
                <div class="er-pagination">
                    <span>Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }}</span>
                    {{ $records->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ── Side Drawer ── --}}
    <aside class="er-drawer" id="erDrawer" role="complementary" aria-label="Record detail">
        <div class="er-drawer-inner">
            <div class="drawer-header">
                <div class="drawer-header-info">
                    <h2 id="drawerTitle">Employment Record</h2>
                    <p id="drawerSubtitle">Select a row to view details</p>
                </div>
                <button class="drawer-close" onclick="closeDrawer()" aria-label="Close detail panel">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="drawer-body" id="drawerBody">
                <div class="drawer-loading">
                    <div class="spinner"></div>
                    <span>Loading…</span>
                </div>
            </div>
        </div>
    </aside>

</div>

<script>
const layout       = document.getElementById('erLayout');
const drawer       = document.getElementById('erDrawer');
const drawerBody   = document.getElementById('drawerBody');
const drawerTitle  = document.getElementById('drawerTitle');
const drawerSub    = document.getElementById('drawerSubtitle');

let activeRow = null;

function openDrawer(id, row) {
    // highlight row
    if (activeRow) activeRow.classList.remove('row-active');
    activeRow = row;
    row.classList.add('row-active');

    layout.classList.add('drawer-open');
    drawerBody.innerHTML = '<div class="drawer-loading"><div class="spinner"></div><span>Loading…</span></div>';
    drawerTitle.textContent = 'Employment Record';
    drawerSub.textContent   = `#${id}`;

    fetch(`{{ url('government/employment-records') }}/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
    .then(renderDrawer)
    .catch(() => {
        drawerBody.innerHTML = '<div class="drawer-loading" style="color:var(--red)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Failed to load record.</div>';
    });
}

function closeDrawer() {
    layout.classList.remove('drawer-open');
    if (activeRow) { activeRow.classList.remove('row-active'); activeRow = null; }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

/* ── Helpers ── */
function fmt(date) {
    if (!date) return `<span class="detail-value muted">—</span>`;
    return `<span class="detail-value mono">${new Date(date).toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'})}</span>`;
}
function val(v, mono = false) {
    if (v === null || v === undefined || v === '') return `<span class="detail-value muted">—</span>`;
    return `<span class="detail-value ${mono ? 'mono' : ''}">${String(v).replace(/</g,'&lt;')}</span>`;
}
function badge(s) {
    const map = { active:'status-active', terminated:'status-terminated', resigned:'status-resigned' };
    const cls = map[s?.toLowerCase()] ?? 'status-default';
    return `<span class="status-badge ${cls}">${s ? s[0].toUpperCase()+s.slice(1) : 'Unknown'}</span>`;
}

/* ── Render ── */
function renderDrawer(r) {
    drawerTitle.textContent = r.job_title ?? 'Employment Record';
    drawerSub.textContent   = `#${r.id} · ${r.employee?.name ?? 'Unknown'}`;

    const disputes = r.disputes?.length
        ? `<span class="dispute-count">
               <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
               ${r.disputes.length} dispute${r.disputes.length > 1 ? 's' : ''}
           </span>`
        : `<span class="dispute-none">No disputes on record</span>`;

    drawerBody.innerHTML = `
        <div class="detail-section">
            <div class="detail-section-title">Employee</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Full Name</span>${val(r.employee?.first_name)} ${val(r.employee?.last_name)}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Employee ID</span>${val(r.employee_id, true)}
                </div>
            </div>
        </div>
        <div class="detail-section">
            <div class="detail-section-title">Employer</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Company</span>${val(r.employer?.company_name)}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Employer ID</span>${val(r.employer_id, true)}
                </div>
            </div>
        </div>
        <div class="detail-section">
            <div class="detail-section-title">Position</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Job Title</span>${val(r.job_title)}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Department</span>${val(r.department)}
                </div>
            </div>
        </div>
        <div class="detail-section">
            <div class="detail-section-title">Timeline & Status</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Start Date</span>${fmt(r.start_date)}
                </div>
                <div class="detail-item">
                    <span class="detail-label">End Date</span>
                    ${r.end_date ? fmt(r.end_date) : '<span class="status-badge status-active">Present</span>'}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>${badge(r.employment_status)}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Exit Reason</span>${val(r.exit_reason)}
                </div>
                <div class="detail-item full">
                    <span class="detail-label">Remarks</span>${val(r.remarks)}
                </div>
            </div>
        </div>
        <div class="detail-section">
            <div class="detail-section-title">Disputes</div>
            ${disputes}
        </div>
    `;
}
</script>
@endsection