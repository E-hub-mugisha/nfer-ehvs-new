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
                        <td class="td-actions">
                            <a href="{{ route('admin.transfer-requests.show', $tr) }}" class="btn-view">View</a>
                            <button
                                type="button"
                                class="btn-delete"
                                onclick="openDeleteModal({{ $tr->id }}, '{{ addslashes($tr->employee->full_name ?? 'this record') }}')"
                                title="Delete">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <polyline points="3 6 5 6 21 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 11v6M14 11v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
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

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="del-overlay" onclick="closeDeleteModal(event)">
    <div class="del-modal">
        <div class="del-icon-wrap">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <polyline points="3 6 5 6 21 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 11v6M14 11v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h3 class="del-title">Delete Transfer Request</h3>
        <p class="del-body">You're about to permanently delete the transfer request for <strong id="del-name"></strong>. This action cannot be undone.</p>
        <div class="del-actions">
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm-delete">Yes, Delete</button>
            </form>
            <button type="button" class="btn-cancel-delete" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
    .tr-page {
        padding: 2rem;
        font-family: 'DM Sans', sans-serif;
        color: #1a2e35;
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
        color: #0d1f26;
        margin: 0 0 .25rem;
    }

    .tr-subtitle {
        font-size: .875rem;
        color: #6b8a95;
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
        background: rgba(234, 179, 8, .10);
        color: #b45309;
        border: 1px solid rgba(234, 179, 8, .35);
    }

    .stat-pill.total {
        background: rgba(0, 166, 103, .10);
        color: #00845a;
        border: 1px solid rgba(0, 166, 103, .3);
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
        color: #6b8a95;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .filter-group select,
    .filter-group input {
        background: #ffffff;
        border: 1px solid #d4e2e6;
        color: #1a2e35;
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
        box-shadow: 0 0 0 3px rgba(0, 166, 103, .1);
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
        color: #6b8a95;
        text-decoration: underline;
        white-space: nowrap;
        margin-bottom: .1rem;
    }

    .btn-clear:hover {
        color: #1a2e35;
    }

    /* Table */
    .tr-table-wrap {
        background: #ffffff;
        border: 1px solid #d4e2e6;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
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
        background: #f4f8f9;
        padding: .85rem 1rem;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #6b8a95;
        text-align: left;
        border-bottom: 1px solid #d4e2e6;
    }

    .tr-table tbody tr {
        border-bottom: 1px solid #eaf2f4;
        transition: background .15s;
    }

    .tr-table tbody tr:last-child {
        border-bottom: none;
    }

    .tr-table tbody tr:hover {
        background: #f4f8f9;
    }

    .tr-table td {
        padding: .85rem 1rem;
        font-size: .875rem;
        color: #2d4a54;
    }

    .td-id {
        color: #6b8a95;
        font-size: .78rem;
    }

    .td-name a {
        color: #00845a;
        text-decoration: none;
        font-weight: 600;
    }

    .td-name a:hover {
        text-decoration: underline;
    }

    .td-role {
        display: block;
        font-weight: 500;
        color: #1a2e35;
    }

    .td-dept {
        display: block;
        font-size: .78rem;
        color: #6b8a95;
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
        color: #b45309;
    }

    .status-badge.approved {
        background: rgba(0, 166, 103, .12);
        color: #00845a;
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, .10);
        color: #dc2626;
    }

    .btn-view {
        background: transparent;
        border: 1px solid #d4e2e6;
        color: #2d4a54;
        border-radius: 7px;
        padding: .3rem .8rem;
        font-size: .8rem;
        text-decoration: none;
        transition: border-color .2s, color .2s;
        white-space: nowrap;
    }

    .btn-view:hover {
        border-color: #00a667;
        color: #00845a;
    }

    /* Empty */
    .tr-empty {
        text-align: center;
        padding: 3.5rem 1rem;
        color: #6b8a95;
    }

    .tr-empty svg {
        margin: 0 auto 1rem;
        display: block;
        opacity: .35;
    }

    /* Pagination */
    .tr-pagination {
        padding: 1rem 1.25rem;
        border-top: 1px solid #eaf2f4;
    }

    .tr-pagination .pagination {
        display: flex;
        gap: .4rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .tr-pagination .page-item .page-link {
        background: #ffffff;
        border: 1px solid #d4e2e6;
        color: #2d4a54;
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

    /* Action cell */
    .td-actions {
        display: flex;
        align-items: center;
        gap: .45rem;
        padding-top: .85rem;
        padding-bottom: .85rem;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: transparent;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: 7px;
        cursor: pointer;
        transition: background .15s, border-color .15s;
        flex-shrink: 0;
    }

    .btn-delete:hover {
        background: rgba(239, 68, 68, .08);
        border-color: #dc2626;
    }

    /* Delete Modal */
    .del-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(13, 31, 38, .45);
        backdrop-filter: blur(3px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .del-overlay.active {
        display: flex;
    }

    .del-modal {
        background: #ffffff;
        border: 1px solid #d4e2e6;
        border-radius: 16px;
        padding: 2rem 2rem 1.75rem;
        max-width: 420px;
        width: calc(100% - 2rem);
        box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        text-align: center;
        animation: del-in .18s ease;
    }

    @keyframes del-in {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .del-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(239, 68, 68, .08);
        border: 1px solid rgba(239, 68, 68, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #dc2626;
    }

    .del-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0d1f26;
        margin: 0 0 .6rem;
    }

    .del-body {
        font-size: .875rem;
        color: #6b8a95;
        line-height: 1.65;
        margin: 0 0 1.5rem;
    }

    .del-body strong {
        color: #1a2e35;
        font-weight: 600;
    }

    .del-actions {
        display: flex;
        gap: .65rem;
        justify-content: center;
    }

    .btn-confirm-delete {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .55rem 1.3rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }

    .btn-confirm-delete:hover {
        background: #b91c1c;
    }

    .btn-cancel-delete {
        background: transparent;
        border: 1px solid #d4e2e6;
        color: #6b8a95;
        border-radius: 8px;
        padding: .55rem 1.2rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        cursor: pointer;
        transition: border-color .15s, color .15s;
    }

    .btn-cancel-delete:hover {
        border-color: #6b8a95;
        color: #1a2e35;
    }
</style>

<script>
    function openDeleteModal(id, name) {
        document.getElementById('del-name').textContent = name;
        // Builds the DELETE route: /admin/transfer-requests/{id}
        document.getElementById('delete-form').action =
            '{{ url("admin/transfer-requests") }}/' + id;
        document.getElementById('delete-modal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal(e) {
        if (e && e.target !== document.getElementById('delete-modal')) return;
        document.getElementById('delete-modal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>

@endsection