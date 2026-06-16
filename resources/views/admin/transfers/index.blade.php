@extends('layouts.app')

@section('title', 'Transfer Requests')

@section('content')
<div class="tr-page">

    {{-- Page Header --}}
    <div class="tr-header">
        <div>
            <h1 class="tr-title">Transfer Requests</h1>
            <p class="tr-subtitle">Manage incoming employee transfer requests</p>
        </div>
        <div class="tr-header-stats">
            <span class="stat-pill pending">{{ $pendingCount ?? 0 }} Pending</span>
            <span class="stat-pill total">{{ $totalCount ?? 0 }} Total</span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="tr-filters">
        <form method="GET" action="{{ route('admin.transfer-requests.index') }}" class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected'  ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="filter-group search-group">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Employee name or employer…">
                <button type="submit" class="btn-search">Search</button>
            </div>
            @if(request()->hasAny(['status','search']))
            <a href="{{ route('admin.transfer-requests.index') }}" class="btn-clear">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="tr-table-wrap">
        @if($transferRequests->isEmpty())
        <div class="tr-empty">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a4 4 0 014-4h4M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h4m0 12v-2m0 2h4m0 0v-2m0 2h4" />
            </svg>
            <p>No transfer requests found.</p>
        </div>
        @else
        <div class="tr-scroll">
            <table class="tr-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>From Employer</th>
                        <th>Requesting Employer</th>
                        <th>Proposed Role</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transferRequests as $tr)
                    <tr>
                        <td class="td-id">{{ $tr->id }}</td>
                        <td class="td-name">
                            <a href="{{ route('admin.transfer-requests.show', $tr) }}">
                                {{ $tr->employee->full_name ?? '—' }}
                            </a>
                        </td>
                        <td>{{ $tr->currentEmployer->name ?? '—' }}</td>
                        <td>{{ $tr->requestingEmployer->name ?? '—' }}</td>
                        <td>
                            <span class="td-role">{{ $tr->proposed_job_title }}</span>
                            @if($tr->proposed_department)
                            <span class="td-dept">{{ $tr->proposed_department }}</span>
                            @endif
                        </td>
                        <td>{{ $tr->proposed_start_date?->format('d M Y') ?? '—' }}</td>
                        <td><span class="status-badge {{ $tr->status }}">{{ ucfirst($tr->status) }}</span></td>
                        <td>{{ $tr->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.transfer-requests.show', $tr) }}" class="btn-view">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tr-pagination">
            {{ $transferRequests->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

<style>
    .tr-page {
        padding: 2rem;
        font-family: 'DM Sans', sans-serif;
        color: #e2e8ea;
    }

    /* Header */
    .tr-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .tr-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 .25rem;
    }

    .tr-subtitle {
        font-size: .875rem;
        color: #7a9198;
        margin: 0;
    }

    .tr-header-stats {
        display: flex;
        gap: .75rem;
        align-items: center;
    }

    .stat-pill {
        padding: .3rem .85rem;
        border-radius: 99px;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .03em;
    }

    .stat-pill.pending {
        background: rgba(234, 179, 8, .12);
        color: #eab308;
        border: 1px solid rgba(234, 179, 8, .25);
    }

    .stat-pill.total {
        background: rgba(0, 166, 103, .12);
        color: #00a667;
        border: 1px solid rgba(0, 166, 103, .25);
    }

    /* Filters */
    .tr-filters {
        margin-bottom: 1.5rem;
    }

    .filter-row {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: .35rem;
    }

    .filter-group label {
        font-size: .75rem;
        font-weight: 600;
        color: #7a9198;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .filter-group select,
    .filter-group input {
        background: #131e21;
        border: 1px solid #1f2f33;
        color: #e2e8ea;
        border-radius: 8px;
        padding: .5rem .85rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        transition: border-color .2s;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        border-color: #00a667;
    }

    .search-group {
        flex-direction: row;
        align-items: flex-end;
        gap: .5rem;
    }

    .search-group label {
        display: none;
    }

    .btn-search {
        background: #00a667;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .52rem 1rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-search:hover {
        background: #009059;
    }

    .btn-clear {
        align-self: flex-end;
        font-size: .8rem;
        color: #7a9198;
        text-decoration: underline;
        white-space: nowrap;
        margin-bottom: .1rem;
    }

    .btn-clear:hover {
        color: #e2e8ea;
    }

    /* Table */
    .tr-table-wrap {
        background: #111b1e;
        border: 1px solid #1a2a2e;
        border-radius: 12px;
        overflow: hidden;
    }

    .tr-scroll {
        overflow-x: auto;
    }

    .tr-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
        min-width: 860px;
    }

    .tr-table thead th {
        background: #0d1719;
        padding: .85rem 1rem;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #7a9198;
        text-align: left;
        border-bottom: 1px solid #1a2a2e;
    }

    .tr-table tbody tr {
        border-bottom: 1px solid #1a2a2e;
        transition: background .15s;
    }

    .tr-table tbody tr:last-child {
        border-bottom: none;
    }

    .tr-table tbody tr:hover {
        background: #142024;
    }

    .tr-table td {
        padding: .85rem 1rem;
        font-size: .875rem;
    }

    .td-id {
        color: #7a9198;
        font-size: .78rem;
    }

    .td-name a {
        color: #00a667;
        text-decoration: none;
        font-weight: 600;
    }

    .td-name a:hover {
        text-decoration: underline;
    }

    .td-role {
        display: block;
        font-weight: 500;
        color: #e2e8ea;
    }

    .td-dept {
        display: block;
        font-size: .78rem;
        color: #7a9198;
        margin-top: .1rem;
    }

    /* Status badges */
    .status-badge {
        padding: .25rem .7rem;
        border-radius: 99px;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: capitalize;
    }

    .status-badge.pending {
        background: rgba(234, 179, 8, .12);
        color: #eab308;
    }

    .status-badge.approved {
        background: rgba(0, 166, 103, .12);
        color: #00a667;
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, .12);
        color: #ef4444;
    }

    .btn-view {
        background: transparent;
        border: 1px solid #1f2f33;
        color: #e2e8ea;
        border-radius: 7px;
        padding: .3rem .8rem;
        font-size: .8rem;
        text-decoration: none;
        transition: border-color .2s, color .2s;
        white-space: nowrap;
    }

    .btn-view:hover {
        border-color: #00a667;
        color: #00a667;
    }

    /* Empty */
    .tr-empty {
        text-align: center;
        padding: 3.5rem 1rem;
        color: #7a9198;
    }

    .tr-empty svg {
        margin: 0 auto 1rem;
        display: block;
        opacity: .4;
    }

    /* Pagination */
    .tr-pagination {
        padding: 1rem 1.25rem;
        border-top: 1px solid #1a2a2e;
    }

    .tr-pagination .pagination {
        display: flex;
        gap: .4rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .tr-pagination .page-item .page-link {
        background: #131e21;
        border: 1px solid #1a2a2e;
        color: #e2e8ea;
        border-radius: 7px;
        padding: .35rem .75rem;
        font-size: .8rem;
        text-decoration: none;
        display: block;
    }

    .tr-pagination .page-item.active .page-link {
        background: #00a667;
        border-color: #00a667;
        color: #fff;
    }

    .tr-pagination .page-item.disabled .page-link {
        opacity: .4;
        pointer-events: none;
    }
</style>
@endsection