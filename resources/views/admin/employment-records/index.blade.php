{{-- resources/views/admin/employment-records/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employment Records')

@section('content')

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

    /* ── Sidebar ─────────────────────────────────────────────────── */
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

    /* ── Main ─────────────────────────────────────────────────────── */
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
        gap: 12px;
    }

    .topbar-title {
        font-size: 16px;
        font-weight: 700;
    }

    .breadcrumb {
        font-size: 12px;
        color: var(--text-dim);
    }

    .breadcrumb a {
        color: var(--accent);
        text-decoration: none;
    }

    .content {
        padding: 28px 32px;
        flex: 1;
    }

    /* ── Stat strip ──────────────────────────────────────────────── */
    .stat-strip {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-chip {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 18px;
        flex: 1;
        cursor: pointer;
        text-decoration: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        display: block;
    }

    .stat-chip:hover,
    .stat-chip.active {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .stat-chip-val {
        font-family: var(--font-mono);
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-chip-val.all {
        color: var(--text);
    }

    .stat-chip-val.active {
        color: var(--green);
    }

    .stat-chip-val.inactive {
        color: var(--text-dim);
    }

    .stat-chip-val.terminated {
        color: var(--red);
    }

    .stat-chip-label {
        font-size: 11px;
        color: var(--text-mid);
        margin-top: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ── Filter bar ──────────────────────────────────────────────── */
    .filter-bar {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .search-wrap svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        color: var(--text-dim);
    }

    .search-input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--border);
        border-radius: 7px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--text);
        background: var(--surface);
        outline: none;
        transition: border-color 0.15s;
    }

    .search-input:focus {
        border-color: var(--accent);
        background: white;
    }

    .filter-select {
        appearance: none;
        border: 1px solid var(--border);
        background: var(--surface);
        padding: 8px 28px 8px 12px;
        border-radius: 7px;
        font-size: 13px;
        font-family: var(--font-sans);
        color: var(--text);
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        outline: none;
        min-width: 160px;
    }

    .filter-select:focus {
        border-color: var(--accent);
    }

    .filter-btn {
        padding: 8px 16px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: var(--font-sans);
        transition: background 0.15s;
    }

    .filter-btn:hover {
        background: var(--accent-dim);
    }

    .clear-link {
        font-size: 12px;
        color: var(--text-dim);
        text-decoration: none;
    }

    .clear-link:hover {
        color: var(--red);
    }

    /* ── Table panel ─────────────────────────────────────────────── */
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
        color: var(--text);
    }

    .panel-count {
        font-family: var(--font-mono);
        font-size: 12px;
        color: var(--text-dim);
    }

    /* ── Data table ──────────────────────────────────────────────── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10.5px;
        font-weight: 600;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
        user-select: none;
    }

    .sort-link {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .sort-link:hover {
        color: var(--accent);
    }

    .sort-arrow {
        font-size: 9px;
    }

    .data-table td {
        padding: 13px 16px;
        font-size: 13px;
        border-bottom: 1px solid var(--surface-mid);
        vertical-align: middle;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover td {
        background: #fafbfc;
    }

    /* ── Cell components ─────────────────────────────────────────── */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .emp-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), #6366f1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .emp-name {
        font-weight: 600;
        color: var(--text);
        font-size: 13px;
    }

    .emp-nid {
        font-size: 11px;
        color: var(--text-dim);
        font-family: var(--font-mono);
    }

    .company-name {
        font-weight: 500;
        color: var(--text);
    }

    .company-dept {
        font-size: 11.5px;
        color: var(--text-dim);
        margin-top: 1px;
    }

    .job-title {
        font-weight: 500;
        color: var(--text);
    }

    .date-range {
        font-size: 11.5px;
        color: var(--text-dim);
        font-family: var(--font-mono);
        margin-top: 2px;
    }

    /* Status badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 5px;
        white-space: nowrap;
    }

    .badge::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }

    .badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .badge.active::before {
        background: var(--green);
    }

    .badge.inactive {
        background: #f1f5f9;
        color: #475569;
    }

    .badge.inactive::before {
        background: #94a3b8;
    }

    .badge.terminated {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge.terminated::before {
        background: var(--red);
    }

    .badge.suspended {
        background: #fef9c3;
        color: #92400e;
    }

    .badge.suspended::before {
        background: var(--amber);
    }

    /* Disputes count pill */
    .dispute-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        background: #fee2e2;
        color: #991b1b;
    }

    .dispute-pill.none {
        background: var(--surface-mid);
        color: var(--text-dim);
    }

    /* Action button */
    .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-mid);
        text-decoration: none;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .view-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: white;
    }

    .view-btn svg {
        width: 12px;
        height: 12px;
    }

    /* ── Empty state ─────────────────────────────────────────────── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 14px;
        color: var(--text-dim);
    }

    .empty-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-mid);
        margin-bottom: 6px;
    }

    .empty-sub {
        font-size: 13px;
        color: var(--text-dim);
    }

    /* ── Pagination ──────────────────────────────────────────────── */
    .pagination-wrap {
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pagination-info {
        font-size: 12px;
        color: var(--text-dim);
        font-family: var(--font-mono);
    }

    .pagination-links {
        display: flex;
        gap: 4px;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        color: var(--text-mid);
        border: 1px solid var(--border);
        transition: all 0.15s;
    }

    .page-btn:hover,
    .page-btn.active {
        background: var(--accent);
        border-color: var(--accent);
        color: white;
    }

    .page-btn.disabled {
        opacity: 0.4;
        pointer-events: none;
    }
</style>

<div class="content">

    {{-- Stat chips --}}
    <div class="stat-strip">
        <a href="{{ route('admin.employment-records.index') }}" class="stat-chip {{ !request('status') ? 'active' : '' }}">
            <div class="stat-chip-val all">{{ number_format($stats['total']) }}</div>
            <div class="stat-chip-label">All Records</div>
        </a>
        <a href="{{ route('admin.employment-records.index', ['status' => 'active']) }}" class="stat-chip {{ request('status') === 'active' ? 'active' : '' }}">
            <div class="stat-chip-val active">{{ number_format($stats['active']) }}</div>
            <div class="stat-chip-label">Active</div>
        </a>
        <a href="{{ route('admin.employment-records.index', ['status' => 'inactive']) }}" class="stat-chip {{ request('status') === 'inactive' ? 'active' : '' }}">
            <div class="stat-chip-val inactive">{{ number_format($stats['inactive']) }}</div>
            <div class="stat-chip-label">Inactive</div>
        </a>
        <a href="{{ route('admin.employment-records.index', ['status' => 'terminated']) }}" class="stat-chip {{ request('status') === 'terminated' ? 'active' : '' }}">
            <div class="stat-chip-val terminated">{{ number_format($stats['terminated']) }}</div>
            <div class="stat-chip-label">Terminated</div>
        </a>
    </div>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.employment-records.index') }}">
        <div class="filter-bar">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Search by employee, NID, employer, job title…"
                    value="{{ request('search') }}">
            </div>

            <select name="status" class="filter-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active'     ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive'   ? 'selected' : '' }}>Inactive</option>
                <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                <option value="suspended" {{ request('status') === 'suspended'  ? 'selected' : '' }}>Suspended</option>
            </select>

            <select name="employer_id" class="filter-select">
                <option value="">All Employers</option>
                @foreach($employers as $employer)
                <option value="{{ $employer->id }}" {{ request('employer_id') == $employer->id ? 'selected' : '' }}>
                    {{ $employer->company_name }}
                </option>
                @endforeach
            </select>

            <select name="sort" class="filter-select" style="min-width:140px">
                <option value="created_at" {{ request('sort','created_at') === 'created_at' ? 'selected' : '' }}>Newest first</option>
                <option value="start_date" {{ request('sort') === 'start_date' ? 'selected' : '' }}>Start date</option>
                <option value="job_title" {{ request('sort') === 'job_title'  ? 'selected' : '' }}>Job title</option>
            </select>

            {{-- Preserve status from chips --}}
            @if(request('status') && !request()->filled('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <button type="submit" class="filter-btn">Filter</button>

            @if(request()->hasAny(['search','status','employer_id','sort']))
            <a href="{{ route('admin.employment-records.index') }}" class="clear-link">Clear</a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                @if(request('search'))
                Results for "<strong>{{ request('search') }}</strong>"
                @else
                Employment Records
                @endif
            </div>
            <div class="panel-count">{{ number_format($records->total()) }} records</div>
        </div>

        @if($records->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="12" y1="11" x2="12" y2="17" />
                <line x1="9" y1="14" x2="15" y2="14" />
            </svg>
            <div class="empty-title">No records found</div>
            <div class="empty-sub">Try adjusting your search or filter criteria.</div>
        </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'job_title', 'dir' => request('sort') === 'job_title' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                            Employee
                            @if(request('sort') === 'job_title') <span class="sort-arrow">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span> @endif
                        </a>
                    </th>
                    <th>Employer / Department</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'job_title', 'dir' => request('sort') === 'job_title' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                            Job Title
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_date', 'dir' => request('sort') === 'start_date' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                            Period
                            @if(request('sort') === 'start_date') <span class="sort-arrow">{{ request('dir') === 'asc' ? '↑' : '↓' }}</span> @endif
                        </a>
                    </th>
                    <th>Status</th>
                    <th>Disputes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr>
                    <td>
                        <div class="employee-cell">
                            <div class="emp-avatar">
                                {{ strtoupper(substr(optional($record->employee)->first_name ?? '?', 0, 1)) }}{{ strtoupper(substr(optional($record->employee)->last_name ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ optional($record->employee)->full_name ?? '—' }}</div>
                                <div class="emp-nid">{{ optional($record->employee)->nid ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="company-name">{{ optional($record->employer)->company_name ?? '—' }}</div>
                        @if($record->department)
                        <div class="company-dept">{{ $record->department }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="job-title">{{ $record->job_title ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="date-range">
                            {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('d M Y') : '—' }}
                            @if($record->end_date)
                            <br>→ {{ \Carbon\Carbon::parse($record->end_date)->format('d M Y') }}
                            @else
                            <br><span style="color:var(--green);font-size:10px;font-weight:600;">PRESENT</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ strtolower($record->employment_status ?? 'inactive') }}">
                            {{ ucfirst($record->employment_status ?? 'Unknown') }}
                        </span>
                    </td>
                    <td>
                        @php $dc = $record->disputes->count(); @endphp
                        <span class="dispute-pill {{ $dc === 0 ? 'none' : '' }}">
                            {{ $dc }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.employment-records.show', $record->id) }}" class="view-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($records->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ number_format($records->total()) }}
            </div>
            <div class="pagination-links">
                @if($records->onFirstPage())
                <span class="page-btn disabled">‹</span>
                @else
                <a href="{{ $records->previousPageUrl() }}" class="page-btn">‹</a>
                @endif

                @foreach($records->getUrlRange(max(1, $records->currentPage()-2), min($records->lastPage(), $records->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page === $records->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($records->hasMorePages())
                <a href="{{ $records->nextPageUrl() }}" class="page-btn">›</a>
                @else
                <span class="page-btn disabled">›</span>
                @endif
            </div>
        </div>
        @endif
        @endif
    </div>

</div>

@endsection