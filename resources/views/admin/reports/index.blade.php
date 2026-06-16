{{-- resources/views/government/reports/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Reports & Analytics — NFER-EHVS')

@section('content')


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --navy: #0f1c2e;
        --navy-mid: #162337;
        --navy-light: #1e3050;
        --navy-border: #253d5c;
        --accent: #3b82f6;
        --accent-dim: #1d4ed8;
        --surface: #f8fafc;
        --surface-mid: #f1f5f9;
        --border: #e2e8f0;
        --text: #0f172a;
        --text-mid: #475569;
        --text-dim: #94a3b8;
        --green: #10b981;
        --red: #ef4444;
        --amber: #f59e0b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --font-mono: 'SF Mono', 'Fira Code', ui-monospace, monospace;
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        --sidebar-w: 260px;
    }

    body {
        font-family: var(--font-sans);
        background: var(--surface);
        color: var(--text);
        display: flex;
        min-height: 100vh;
        font-size: 14px;
        line-height: 1.5;
    }

    /* ── Sidebar (same as dashboard) ────────────────────────────── */
    .sidebar {
        width: var(--sidebar-w);
        background: var(--navy);
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        z-index: 100;
    }

    .sidebar-brand {
        padding: 28px 24px 20px;
        border-bottom: 1px solid var(--navy-border);
    }

    .brand-mark {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-icon {
        width: 34px;
        height: 34px;
        background: var(--accent);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-icon svg {
        width: 18px;
        height: 18px;
        color: white;
    }

    .brand-name {
        color: white;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .brand-sub {
        color: var(--text-dim);
        font-size: 11px;
        font-family: var(--font-mono);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .sidebar-section {
        padding: 20px 12px 8px;
    }

    .sidebar-section-label {
        color: var(--text-dim);
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0 12px;
        margin-bottom: 6px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 7px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: background 0.15s, color 0.15s;
        margin-bottom: 2px;
    }

    .nav-item:hover {
        background: var(--navy-light);
        color: white;
    }

    .nav-item.active {
        background: var(--accent);
        color: white;
    }

    .nav-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .nav-badge {
        margin-left: auto;
        background: var(--red);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        font-family: var(--font-mono);
    }

    .nav-badge.amber {
        background: var(--amber);
        color: var(--navy);
    }

    .nav-badge.blue {
        background: var(--accent);
    }

    .sidebar-footer {
        margin-top: auto;
        padding: 16px 12px;
        border-top: 1px solid var(--navy-border);
    }

    .user-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 7px;
        background: var(--navy-light);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: var(--accent-dim);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 12px;
    }

    .user-name {
        color: white;
        font-size: 13px;
        font-weight: 600;
    }

    .user-role {
        color: var(--text-dim);
        font-size: 11px;
    }

    /* ── Main ───────────────────────────────────────────────────── */
    .main {
        margin-left: var(--sidebar-w);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .topbar {
        background: white;
        border-bottom: 1px solid var(--border);
        padding: 0 32px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .topbar-title {
        font-size: 16px;
        font-weight: 700;
    }

    .topbar-breadcrumb {
        font-size: 12px;
        color: var(--text-dim);
    }

    .topbar-breadcrumb a {
        color: var(--accent);
        text-decoration: none;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Period selector */
    .period-select {
        appearance: none;
        border: 1px solid var(--border);
        background: white;
        padding: 6px 28px 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--text);
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
    }

    .export-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid var(--border);
        background: white;
        color: var(--text-mid);
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
        position: relative;
    }

    .export-btn:hover {
        background: var(--surface-mid);
        border-color: var(--accent);
    }

    .export-btn svg {
        width: 13px;
        height: 13px;
    }

    /* Export dropdown */
    .export-wrap {
        position: relative;
    }

    .export-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        z-index: 200;
        overflow: hidden;
    }

    .export-wrap:hover .export-dropdown,
    .export-dropdown:hover {
        display: block;
    }

    .export-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--text);
        text-decoration: none;
        transition: background 0.1s;
    }

    .export-item:hover {
        background: var(--surface-mid);
    }

    .export-item svg {
        width: 13px;
        height: 13px;
        color: var(--text-dim);
    }

    .export-divider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 4px 0;
    }

    /* ── Content ────────────────────────────────────────────────── */
    .content {
        padding: 28px 32px;
        flex: 1;
    }

    /* ── Rate cards row ─────────────────────────────────────────── */
    .rate-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .rate-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
    }

    .rate-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 0 0 10px 10px;
    }

    .rate-card.green::after {
        background: var(--green);
    }

    .rate-card.blue::after {
        background: var(--blue);
    }

    .rate-card.amber::after {
        background: var(--amber);
    }

    .rate-card.purple::after {
        background: var(--purple);
    }

    .rate-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-mid);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 10px;
    }

    .rate-value {
        font-family: var(--font-mono);
        font-size: 36px;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .rate-value.green {
        color: var(--green);
    }

    .rate-value.blue {
        color: var(--blue);
    }

    .rate-value.amber {
        color: var(--amber);
    }

    .rate-value.purple {
        color: var(--purple);
    }

    .rate-sub {
        margin-top: 8px;
        font-size: 11.5px;
        color: var(--text-dim);
    }

    /* ── Section heading ────────────────────────────────────────── */
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        margin-top: 28px;
    }

    .section-head:first-of-type {
        margin-top: 0;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .section-meta {
        font-size: 12px;
        color: var(--text-dim);
        font-family: var(--font-mono);
    }

    /* ── Panel ──────────────────────────────────────────────────── */
    .panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    .panel-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-title {
        font-size: 13px;
        font-weight: 700;
    }

    .panel-body {
        padding: 20px;
    }

    /* ── Chart grids ────────────────────────────────────────────── */
    .chart-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .chart-grid-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .chart-wrap {
        position: relative;
        height: 200px;
    }

    .chart-wrap.tall {
        height: 260px;
    }

    /* ── Donut legend ───────────────────────────────────────────── */
    .donut-legend {
        margin-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .legend-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-label {
        font-size: 12px;
        color: var(--text-mid);
        flex: 1;
        text-transform: capitalize;
    }

    .legend-count {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
    }

    .legend-pct {
        font-family: var(--font-mono);
        font-size: 11px;
        color: var(--text-dim);
        min-width: 36px;
        text-align: right;
    }

    /* ── Summary table ──────────────────────────────────────────── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--border);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .summary-cell {
        background: white;
        padding: 16px 20px;
    }

    .summary-cell-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .summary-cell-value {
        font-family: var(--font-mono);
        font-size: 24px;
        font-weight: 700;
        margin-top: 4px;
    }

    .summary-cell-value.green {
        color: var(--green);
    }

    .summary-cell-value.red {
        color: var(--red);
    }

    .summary-cell-value.blue {
        color: var(--blue);
    }

    .summary-cell-value.amber {
        color: var(--amber);
    }

    .summary-cell-value.purple {
        color: var(--purple);
    }

    .summary-cell-value.neutral {
        color: var(--text);
    }

    .summary-cell-sub {
        font-size: 11.5px;
        color: var(--text-dim);
        margin-top: 2px;
    }

    /* ── Top employers table ────────────────────────────────────── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        font-size: 10.5px;
        font-weight: 600;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 10px 16px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
    }

    .data-table td {
        padding: 11px 16px;
        font-size: 13px;
        border-bottom: 1px solid var(--surface-mid);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background: var(--surface);
    }

    .rank-num {
        font-family: var(--font-mono);
        font-size: 11px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .bar-mini {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bar-mini-track {
        flex: 1;
        height: 6px;
        background: var(--surface-mid);
        border-radius: 3px;
        overflow: hidden;
    }

    .bar-mini-fill {
        height: 100%;
        background: var(--accent);
        border-radius: 3px;
    }

    .bar-mini-val {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        min-width: 24px;
        text-align: right;
    }

    .status-dot {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-dot::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-dot.approved::before {
        background: var(--green);
    }

    .status-dot.pending::before {
        background: var(--amber);
    }

    .status-dot.rejected::before {
        background: var(--red);
    }

    /* ── District chart ─────────────────────────────────────────── */
    .district-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .district-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .district-name {
        font-size: 12.5px;
        color: var(--text-mid);
        min-width: 110px;
        text-transform: capitalize;
    }

    .district-bar-track {
        flex: 1;
        height: 8px;
        background: var(--surface-mid);
        border-radius: 4px;
        overflow: hidden;
    }

    .district-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--blue), var(--purple));
        border-radius: 4px;
    }

    .district-val {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        min-width: 30px;
        text-align: right;
    }

    @media (max-width: 1200px) {
        .rate-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-grid-3 {
            grid-template-columns: 1fr 1fr;
        }

        .chart-grid-2 {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main {
            margin-left: 0;
        }

        .rate-grid {
            grid-template-columns: 1fr 1fr;
        }

        .chart-grid-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- Topbar --}}
<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-title">Reports</div>
        <div class="topbar-breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a> / Reports
        </div>
    </div>
    <div class="topbar-right">
        {{-- Period filter --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" id="periodForm">
            <select name="period" class="period-select" onchange="document.getElementById('periodForm').submit()">
                <option value="3" {{ $period == 3  ? 'selected' : '' }}>Last 3 months</option>
                <option value="6" {{ $period == 6  ? 'selected' : '' }}>Last 6 months</option>
                <option value="12" {{ $period == 12 ? 'selected' : '' }}>Last 12 months</option>
                <option value="24" {{ $period == 24 ? 'selected' : '' }}>Last 24 months</option>
            </select>
        </form>

        {{-- Export dropdown --}}
        <div class="export-wrap">
            <button class="export-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Export CSV
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>
            <div class="export-dropdown">
                <a href="{{ route('admin.reports.export', 'summary') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    Summary Report
                </a>
                <hr class="export-divider">
                <a href="{{ route('admin.reports.export', 'employees') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    All Employees
                </a>
                <a href="{{ route('admin.reports.export', 'employers') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    All Employers
                </a>
                <a href="{{ route('admin.reports.export', 'records') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    Employment Records
                </a>
                <a href="{{ route('admin.reports.export', 'disputes') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Disputes
                </a>
                <a href="{{ route('admin.reports.export', 'transfers') }}" class="export-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                    </svg>
                    Transfer Requests
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">

    {{-- ── Rate cards ─────────────────────────────────────────── --}}
    <div class="rate-grid">
        <div class="rate-card green">
            <div class="rate-label">Dispute Resolution Rate</div>
            <div class="rate-value green">{{ $rates['resolutionRate'] }}%</div>
            <div class="rate-sub">{{ number_format($summary['resolved_disputes']) }} of {{ number_format($summary['total_disputes']) }} disputes resolved</div>
        </div>
        <div class="rate-card blue">
            <div class="rate-label">Employer Approval Rate</div>
            <div class="rate-value blue">{{ $rates['approvalRate'] }}%</div>
            <div class="rate-sub">{{ number_format($summary['approved_employers']) }} of {{ number_format($summary['total_employers']) }} employers approved</div>
        </div>
        <div class="rate-card amber">
            <div class="rate-label">Active Employment Rate</div>
            <div class="rate-value amber">{{ $rates['activeRate'] }}%</div>
            <div class="rate-sub">{{ number_format($summary['active_records']) }} of {{ number_format($summary['total_records']) }} records active</div>
        </div>
        <div class="rate-card purple">
            <div class="rate-label">Transfer Approval Rate</div>
            <div class="rate-value purple">{{ $rates['transferApprovalRate'] }}%</div>
            <div class="rate-sub">{{ number_format($summary['approved_transfers']) }} of {{ number_format($summary['total_transfers']) }} transfers approved</div>
        </div>
    </div>

    {{-- ── Summary cells ───────────────────────────────────────── --}}
    <div class="summary-grid">
        <div class="summary-cell">
            <div class="summary-cell-label">Total Employees</div>
            <div class="summary-cell-value neutral">{{ number_format($summary['total_employees']) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-cell-label">Approved Employers</div>
            <div class="summary-cell-value green">{{ number_format($summary['approved_employers']) }}</div>
            <div class="summary-cell-sub">{{ $summary['pending_employers'] }} pending approval</div>
        </div>
        <div class="summary-cell">
            <div class="summary-cell-label">Active Records</div>
            <div class="summary-cell-value blue">{{ number_format($summary['active_records']) }}</div>
            <div class="summary-cell-sub">of {{ number_format($summary['total_records']) }} total</div>
        </div>
        <div class="summary-cell">
            <div class="summary-cell-label">Open Disputes</div>
            <div class="summary-cell-value red">{{ number_format($summary['open_disputes']) }}</div>
            <div class="summary-cell-sub">{{ $summary['resolved_disputes'] }} resolved</div>
        </div>
        <div class="summary-cell">
            <div class="summary-cell-label">Pending Transfers</div>
            <div class="summary-cell-value amber">{{ number_format($summary['pending_transfers']) }}</div>
            <div class="summary-cell-sub">{{ $summary['approved_transfers'] }} approved</div>
        </div>
        <div class="summary-cell">
            <div class="summary-cell-label">Total Transfers</div>
            <div class="summary-cell-value neutral">{{ number_format($summary['total_transfers']) }}</div>
        </div>
    </div>

    {{-- ── Trend chart ─────────────────────────────────────────── --}}
    <div class="section-head">
        <div class="section-title">Registration Trends</div>
        <div class="section-meta">Last {{ $period }} months</div>
    </div>
    <div class="panel" style="margin-bottom:16px;">
        <div class="panel-header">
            <div class="panel-title">New Registrations Over Time</div>
        </div>
        <div class="panel-body">
            <div class="chart-wrap tall">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Disputes & Records & Transfers breakdown ────────────── --}}
    <div class="section-head">
        <div class="section-title">Status Breakdown</div>
    </div>
    <div class="chart-grid-3">
        {{-- Employment records by status --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Employment Records</div>
            </div>
            <div class="panel-body">
                <div class="chart-wrap"><canvas id="recordsChart"></canvas></div>
                <div class="donut-legend">
                    @php
                    $rTotal = $recordsByStatus->sum();
                    $rColours = ['active' => '#10b981', 'inactive' => '#94a3b8', 'terminated' => '#ef4444', 'suspended' => '#f59e0b'];
                    @endphp
                    @foreach($recordsByStatus as $status => $count)
                    <div class="legend-row">
                        <div class="legend-dot" style="background:{{ $rColours[$status] ?? '#6366f1' }}"></div>
                        <div class="legend-label">{{ $status }}</div>
                        <div class="legend-count">{{ number_format($count) }}</div>
                        <div class="legend-pct">{{ $rTotal ? round($count/$rTotal*100) : 0 }}%</div>
                    </div>
                    @endforeach
                    @if($recordsByStatus->isEmpty())
                    <div style="color:var(--text-dim);font-size:12px;">No records yet.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Disputes by status --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Disputes</div>
            </div>
            <div class="panel-body">
                <div class="chart-wrap"><canvas id="disputesChart"></canvas></div>
                <div class="donut-legend">
                    @php
                    $dTotal = $disputesByStatus->sum();
                    $dColours = ['open' => '#ef4444', 'resolved' => '#10b981', 'closed' => '#94a3b8', 'pending' => '#f59e0b'];
                    @endphp
                    @foreach($disputesByStatus as $status => $count)
                    <div class="legend-row">
                        <div class="legend-dot" style="background:{{ $dColours[$status] ?? '#6366f1' }}"></div>
                        <div class="legend-label">{{ $status }}</div>
                        <div class="legend-count">{{ number_format($count) }}</div>
                        <div class="legend-pct">{{ $dTotal ? round($count/$dTotal*100) : 0 }}%</div>
                    </div>
                    @endforeach
                    @if($disputesByStatus->isEmpty())
                    <div style="color:var(--text-dim);font-size:12px;">No disputes yet.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Transfers by status --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Transfer Requests</div>
            </div>
            <div class="panel-body">
                <div class="chart-wrap"><canvas id="transfersChart"></canvas></div>
                <div class="donut-legend">
                    @php
                    $tTotal = $transfersByStatus->sum();
                    $tColours = ['pending' => '#f59e0b', 'approved' => '#10b981', 'rejected' => '#ef4444'];
                    @endphp
                    @foreach($transfersByStatus as $status => $count)
                    <div class="legend-row">
                        <div class="legend-dot" style="background:{{ $tColours[$status] ?? '#6366f1' }}"></div>
                        <div class="legend-label">{{ $status }}</div>
                        <div class="legend-count">{{ number_format($count) }}</div>
                        <div class="legend-pct">{{ $tTotal ? round($count/$tTotal*100) : 0 }}%</div>
                    </div>
                    @endforeach
                    @if($transfersByStatus->isEmpty())
                    <div style="color:var(--text-dim);font-size:12px;">No transfer requests yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Top employers + District spread ────────────────────── --}}
    <div class="section-head">
        <div class="section-title">Geographic & Employer Distribution</div>
    </div>
    <div class="chart-grid-2">
        {{-- Top employers --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Top Employers by Record Count</div>
                <a href="{{ route('government.employers.index') }}" style="font-size:12px;color:var(--accent);text-decoration:none;">View all →</a>
            </div>
            @php $maxCount = $topEmployers->max('employment_records_count') ?: 1; @endphp
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Records</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topEmployers as $i => $employer)
                    <tr>
                        <td><span class="rank-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <a href="{{ route('admin.employers.show', $employer->id) }}" style="color:var(--text);text-decoration:none;font-weight:500;">
                                {{ $employer->company_name }}
                            </a>
                        </td>
                        <td>
                            <span class="status-dot {{ $employer->status }}">{{ ucfirst($employer->status) }}</span>
                        </td>
                        <td>
                            <div class="bar-mini">
                                <div class="bar-mini-track">
                                    <div class="bar-mini-fill" style="width:{{ round($employer->employment_records_count / $maxCount * 100) }}%"></div>
                                </div>
                                <div class="bar-mini-val">{{ $employer->employment_records_count }}</div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--text-dim);padding:24px;">No employers yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Employee distribution by district --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Employees by District</div>
                <span style="font-size:11px;color:var(--text-dim);font-family:var(--font-mono);">Top 10</span>
            </div>
            <div class="panel-body">
                @php $maxDistrict = $employeesByDistrict->max() ?: 1; @endphp
                @if($employeesByDistrict->isNotEmpty())
                <div class="district-list">
                    @foreach($employeesByDistrict as $district => $count)
                    <div class="district-row">
                        <div class="district-name">{{ $district ?: 'Unknown' }}</div>
                        <div class="district-bar-track">
                            <div class="district-bar-fill" style="width:{{ round($count / $maxDistrict * 100) }}%"></div>
                        </div>
                        <div class="district-val">{{ number_format($count) }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="color:var(--text-dim);font-size:13px;text-align:center;padding:24px 0;">No district data available.</div>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /content --}}


<script>
    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#94a3b8';

    // ── Trend line chart ─────────────────────────────────────────────────
    const labels = @json($trendLabels);
    const employees = @json($trendEmployees);
    const employers = @json($trendEmployers);
    const records = @json($trendRecords);
    const disputes = @json($trendDisputes);

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                    label: 'Employees',
                    data: employees,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.35,
                    fill: true,
                },
                {
                    label: 'Employers',
                    data: employers,
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.35,
                },
                {
                    label: 'Records',
                    data: records,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    borderDash: [4, 4],
                    tension: 0.35,
                },
                {
                    label: 'Disputes',
                    data: disputes,
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    borderDash: [2, 3],
                    tension: 0.35,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        padding: 16,
                        usePointStyle: true,
                        pointStyleWidth: 8
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: '#f1f5f9'
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });

    // ── Shared donut config ──────────────────────────────────────────────
    const donut = (id, labels, data, colors) => new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                    }
                }
            }
        }
    });

    const recordsData = @json($recordsByStatus);
    const disputesData = @json($disputesByStatus);
    const transfersData = @json($transfersByStatus);

    if (Object.keys(recordsData).length) {
        donut('recordsChart',
            Object.keys(recordsData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            Object.values(recordsData),
            ['#10b981', '#94a3b8', '#ef4444', '#f59e0b', '#6366f1']
        );
    }

    if (Object.keys(disputesData).length) {
        donut('disputesChart',
            Object.keys(disputesData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            Object.values(disputesData),
            ['#ef4444', '#10b981', '#94a3b8', '#f59e0b']
        );
    }

    if (Object.keys(transfersData).length) {
        donut('transfersChart',
            Object.keys(transfersData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            Object.values(transfersData),
            ['#f59e0b', '#10b981', '#ef4444']
        );
    }
</script>


@endsection