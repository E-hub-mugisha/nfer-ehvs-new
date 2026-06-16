@extends('layouts.app')

@section('title', 'Disputes Management')

@section('content')

<style>
    /* ── Page Layout ─────────────────────────────────── */
    .disputes-page {
        padding: 1.5rem 2rem;
        max-width: 1280px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 .25rem;
    }

    .page-subtitle {
        font-size: .875rem;
        color: #6b7280;
        margin: 0;
    }

    /* ── Alert ───────────────────────────────────────── */
    .alert {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .875rem 1rem;
        border-radius: .5rem;
        font-size: .875rem;
        margin-bottom: 1.25rem;
    }

    .alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    /* ── Stats ───────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card__icon {
        width: 42px;
        height: 42px;
        border-radius: .5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-card__icon--blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-card__icon--amber {
        background: #fffbeb;
        color: #d97706;
    }

    .stat-card__icon--green {
        background: #f0fdf4;
        color: #16a34a;
    }

    .stat-card__icon--red {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-card__content {
        display: flex;
        flex-direction: column;
    }

    .stat-card__value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }

    .stat-card__label {
        font-size: .75rem;
        color: #6b7280;
        margin-top: .25rem;
    }

    /* ── Filter Bar ──────────────────────────────────── */
    .filter-bar {
        margin-bottom: 1rem;
    }

    .filter-form {
        display: flex;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .search-input {
        padding-left: 2.25rem !important;
    }

    .form-input,
    .form-select {
        height: 38px;
        padding: 0 .875rem;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        font-size: .875rem;
        color: #374151;
        background: #fff;
        outline: none;
        transition: border-color .15s;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px #dbeafe;
    }

    .form-input {
        min-width: 240px;
    }

    .form-select {
        min-width: 160px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: 0 1.125rem;
        height: 38px;
        border-radius: .5rem;
        font-size: .875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-ghost {
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .btn-ghost:hover {
        background: #f9fafb;
        color: #374151;
    }

    /* ── Table Card ──────────────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .875rem;
    }

    .data-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table th {
        padding: .75rem 1rem;
        text-align: left;
        font-size: .75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .05em;
        white-space: nowrap;
    }

    .data-table td {
        padding: .875rem 1rem;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .text-muted {
        color: #9ca3af;
    }

    .text-mono {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: .8125rem;
    }

    .employee-cell {
        display: flex;
        align-items: center;
        gap: .625rem;
    }

    .employee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: .75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .employee-name {
        font-weight: 500;
        color: #111827;
        white-space: nowrap;
    }

    /* ── Badges ──────────────────────────────────────── */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: .25rem .625rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge--amber {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge--green {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge--red {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .badge--blue {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* ── View Link ───────────────────────────────────── */
    .btn-link {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        font-size: .8125rem;
        font-weight: 500;
        color: #2563eb;
        text-decoration: none;
        padding: .3rem .75rem;
        border-radius: .375rem;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        transition: all .15s;
    }

    .btn-link:hover {
        background: #dbeafe;
    }

    /* ── Empty State ─────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }

    .empty-state svg {
        display: block;
        margin: 0 auto 1rem;
        opacity: .4;
    }

    .empty-state h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin: 0 0 .375rem;
    }

    .empty-state p {
        font-size: .875rem;
        margin: 0;
    }

    /* ── Pagination ──────────────────────────────────── */
    .pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .875rem 1rem;
        border-top: 1px solid #f3f4f6;
        font-size: .8125rem;
        color: #6b7280;
    }

    /* ── Responsive ──────────────────────────────────── */
    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .disputes-page {
            padding: 1rem;
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }

        .form-input,
        .form-select {
            min-width: 100%;
        }
    }
</style>

<div class="disputes-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header__left">
            <h1 class="page-title">Disputes</h1>
            <p class="page-subtitle">Review and manage employment record disputes filed by employees.</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
            </div>
            <div class="stat-card__content">
                <span class="stat-card__value">{{ $stats['total'] }}</span>
                <span class="stat-card__label">Total Disputes</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--amber">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
            <div class="stat-card__content">
                <span class="stat-card__value">{{ $stats['pending'] }}</span>
                <span class="stat-card__label">Pending</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div class="stat-card__content">
                <span class="stat-card__value">{{ $stats['resolved'] }}</span>
                <span class="stat-card__label">Resolved</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--red">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </div>
            <div class="stat-card__content">
                <span class="stat-card__value">{{ $stats['rejected'] }}</span>
                <span class="stat-card__label">Rejected</span>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.disputes.index') }}" class="filter-form">
            <div class="filter-group">
                <div class="search-input-wrap">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        class="form-input search-input"
                        placeholder="Search by name or NID…"
                        value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending'      ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="resolved" {{ request('status') === 'resolved'     ? 'selected' : '' }}>Resolved</option>
                    <option value="rejected" {{ request('status') === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.disputes.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        @if($disputes->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>
            <h3>No disputes found</h3>
            <p>No disputes match your current filters.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>NID</th>
                        <th>Employer</th>
                        <th>Job Title</th>
                        <th>Filed On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disputes as $dispute)
                    <tr>
                        <td class="text-muted">{{ $disputes->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="employee-cell">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($dispute->employee->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="employee-name">{{ $dispute->employee->first_name ?? '—' }} {{ $dispute->employee->last_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="text-mono">{{ $dispute->employee->nid ?? '—' }}</td>
                        <td>{{ $dispute->employmentRecord->employer->company_name ?? '—' }}</td>
                        <td>{{ $dispute->employmentRecord->job_title ?? '—' }}</td>
                        <td>{{ $dispute->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge--{{ $dispute->status === 'pending' ? 'amber' : ($dispute->status === 'resolved' ? 'green' : ($dispute->status === 'rejected' ? 'red' : 'blue')) }}">
                                {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.disputes.show', $dispute) }}" class="btn-link">
                                View
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($disputes->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">
                Showing {{ $disputes->firstItem() }}–{{ $disputes->lastItem() }} of {{ $disputes->total() }} disputes
            </span>
            {{ $disputes->links() }}
        </div>
        @endif
        @endif
    </div>

</div>


@endsection