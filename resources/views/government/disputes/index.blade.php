@extends('layouts.app')

@section('title', 'Employment Disputes')

@section('content')

<div class="disputes-wrapper">

    {{-- Page Header --}}
    <div class="d-flex align-items-start justify-content-between mb-4">
        <div>
            <h1 class="page-title">Employment Disputes</h1>
            <p class="page-subtitle">Review and manage employment record disputes submitted by employees</p>
        </div>
        <span class="total-badge">{{ number_format($statusCounts['all']) }} Total</span>
    </div>

    {{-- Status Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('government.disputes.index') }}"
                class="stat-card stat-card--all {{ !request('status') ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-stack"></i></div>
                <div class="stat-count">{{ number_format($statusCounts['all']) }}</div>
                <div class="stat-label">All Disputes</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('government.disputes.index', ['status' => 'pending']) }}"
                class="stat-card stat-card--pending {{ request('status') === 'pending' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-count">{{ number_format($statusCounts['pending']) }}</div>
                <div class="stat-label">Pending</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('government.disputes.index', ['status' => 'under_review']) }}"
                class="stat-card stat-card--review {{ request('status') === 'under_review' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-search"></i></div>
                <div class="stat-count">{{ number_format($statusCounts['under_review']) }}</div>
                <div class="stat-label">Under Review</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('government.disputes.index', ['status' => 'resolved']) }}"
                class="stat-card stat-card--resolved {{ request('status') === 'resolved' ? 'active' : '' }}">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-count">{{ number_format($statusCounts['resolved']) }}</div>
                <div class="stat-label">Resolved</div>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-bar mb-4">
        <form method="GET" action="{{ route('government.disputes.index') }}"
            class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="filter-label">Search Employee</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Name or National ID..." value="{{ request('search') }}">
                </div>
            </div>
            <div style="min-width:160px;">
                <label class="filter-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending'      ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="resolved" {{ request('status') === 'resolved'     ? 'selected' : '' }}>Resolved</option>
                    <option value="rejected" {{ request('status') === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-filter">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','status']))
                <a href="{{ route('government.disputes.index') }}" class="btn btn-outline-secondary btn-filter">
                    <i class="bi bi-x-circle me-1"></i> Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Table --}}
    <div class="disputes-table-card">
        @if($disputes->isEmpty())
        <div class="empty-state py-5 text-center">
            <div class="empty-icon mb-3"><i class="bi bi-inbox"></i></div>
            <h5 class="empty-title">No disputes found</h5>
            <p class="empty-text">No disputes match your current filters.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table disputes-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Employment Record</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disputes as $dispute)
                    <tr>
                        <td class="text-muted small">{{ $dispute->id }}</td>

                        <td>
                            <div class="employee-cell">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr(optional($dispute->employee)->full_name ?? 'U', 0, 1)) }}
                                </div>

                                <div>
                                    <div class="employee-name">
                                        {{ optional($dispute->employee)->full_name ?? 'Unknown Employee' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ optional($dispute->employee)->nid ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($dispute->employmentRecord)
                            <span class="record-ref">
                                <i class="bi bi-briefcase me-1"></i> Record #{{ $dispute->employment_record_id }}
                            </span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <div class="description-cell">
                                {{ Str::limit($dispute->description, 80) }}
                            </div>
                        </td>

                        <td>
                            @include('government.disputes._status_badge', ['status' => $dispute->status])
                        </td>

                        <td class="text-muted small">
                            {{ $dispute->created_at->format('d M Y') }}
                            <div style="font-size:.75rem;">{{ $dispute->created_at->diffForHumans() }}</div>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('government.disputes.show', $dispute) }}"
                                class="btn btn-sm btn-view">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($disputes->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
            <div class="text-muted small">
                Showing {{ $disputes->firstItem() }}–{{ $disputes->lastItem() }}
                of {{ $disputes->total() }} disputes
            </div>
            {{ $disputes->links() }}
        </div>
        @endif
        @endif
    </div>

</div>

<style>
    /* .disputes-wrapper   { max-width: 1200px; } */

    /* Header */
    .page-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a2a5e;
        margin-bottom: .2rem;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: .9rem;
        margin: 0;
    }

    .total-badge {
        background: #eef0fb;
        color: #1a2a5e;
        font-weight: 600;
        font-size: .85rem;
        padding: .4rem .9rem;
        border-radius: 50px;
        border: 1px solid #d0d5ef;
    }

    /* Stat Cards */
    .stat-card {
        display: block;
        text-decoration: none;
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 14px;
        padding: 1.1rem 1rem;
        transition: all .2s ease;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 14px 14px 0 0;
    }

    .stat-card--all::before {
        background: #1a2a5e;
    }

    .stat-card--pending::before {
        background: #f59e0b;
    }

    .stat-card--review::before {
        background: #3b82f6;
    }

    .stat-card--resolved::before {
        background: #10b981;
    }

    .stat-card:hover,
    .stat-card.active {
        border-color: transparent;
        box-shadow: 0 4px 20px rgba(26, 42, 94, .12);
        transform: translateY(-2px);
    }

    .stat-icon {
        font-size: 1.2rem;
        margin-bottom: .5rem;
        opacity: .65;
    }

    .stat-card--pending .stat-icon {
        color: #f59e0b;
    }

    .stat-card--review .stat-icon {
        color: #3b82f6;
    }

    .stat-card--resolved .stat-icon {
        color: #10b981;
    }

    .stat-card--all .stat-icon {
        color: #1a2a5e;
    }

    .stat-count {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a2a5e;
        line-height: 1;
        margin-bottom: .25rem;
    }

    .stat-label {
        font-size: .75rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* Filter Bar */
    .filter-bar {
        background: #f8f9fc;
        border: 1px solid #e8eaf0;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
    }

    .filter-label {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        margin-bottom: .35rem;
        display: block;
    }

    .btn-filter {
        padding: .44rem 1rem;
        font-size: .875rem;
    }

    /* Table Card */
    .disputes-table-card {
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 14px;
        overflow: hidden;
    }

    .disputes-table {
        margin: 0;
    }

    .disputes-table thead th {
        background: #f8f9fc;
        font-size: .73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        border-bottom: 1.5px solid #e8eaf0;
        padding: .85rem 1rem;
    }

    .disputes-table tbody td {
        padding: .9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f8;
    }

    .disputes-table tbody tr:last-child td {
        border-bottom: none;
    }

    .disputes-table tbody tr:hover {
        background: #fafbff;
    }

    /* Employee Cell */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: .65rem;
    }

    .employee-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, #1a2a5e, #2d4a9e);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .employee-name {
        font-weight: 600;
        color: #1a2a5e;
        font-size: .9rem;
    }

    /* Misc */
    .record-ref {
        font-size: .82rem;
        color: #4b6cb7;
        font-weight: 500;
    }

    .description-cell {
        font-size: .85rem;
        color: #495057;
        max-width: 280px;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .75rem;
        font-weight: 600;
        padding: .3rem .7rem;
        border-radius: 50px;
        text-transform: capitalize;
        letter-spacing: .2px;
    }

    .status-badge--pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge--under_review {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-badge--resolved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge--rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* View Button */
    .btn-view {
        background: #eef0fb;
        color: #1a2a5e;
        border: 1px solid #d0d5ef;
        font-size: .8rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all .2s;
    }

    .btn-view:hover {
        background: #1a2a5e;
        color: #fff;
        border-color: #1a2a5e;
    }

    /* Empty State */
    .empty-icon {
        font-size: 3rem;
        color: #c5cae9;
    }

    .empty-title {
        color: #3d4a6b;
        font-weight: 600;
    }

    .empty-text {
        color: #9aa0b4;
        font-size: .9rem;
    }

    /* Pagination */
    .pagination {
        margin: 0;
        gap: .2rem;
    }

    .page-link {
        border-radius: 8px !important;
        font-size: .82rem;
        padding: .3rem .65rem;
        color: #1a2a5e;
        border-color: #e8eaf0;
    }

    .page-item.active .page-link {
        background-color: #1a2a5e;
        border-color: #1a2a5e;
    }
</style>
@endsection