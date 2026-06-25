@extends('layouts.app')

@section('title', 'Employers')

@section('content')

<style>
    .employers-stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(14,32,57,.05);
        transition: box-shadow .2s;
    }

    .employers-stat-card:hover {
        box-shadow: 0 4px 18px rgba(14,32,57,.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-icon-total    { background: rgba(14,32,57,.07);   color: var(--navy); }
    .stat-icon-approved { background: rgba(16,185,129,.1);  color: #059669; }
    .stat-icon-pending  { background: rgba(234,179,8,.1);   color: #b45309; }
    .stat-icon-rejected { background: rgba(239,68,68,.1);   color: #dc2626; }

    .stat-value {
        font-family: 'Sora', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--navy);
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 3px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
    }

    /* Filters bar */
    .filters-bar {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 1px 4px rgba(14,32,57,.04);
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .search-input-wrap i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 15px;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 14px 9px 38px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #1a2e45;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }

    .search-input:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-pale);
    }

    .filter-select {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #1a2e45;
        outline: none;
        transition: border-color .2s;
        background: #fff;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-pale);
    }

    /* Table card */
    .table-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(14,32,57,.06);
        overflow: hidden;
    }

    .table-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafbfc;
    }

    .table-card-header h6 {
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin: 0;
    }

    /* Table styles */
    .employers-table { font-size: 13.5px; }

    .employers-table thead th {
        font-family: 'Sora', sans-serif;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        background: #f8fafc;
        border-bottom: 2px solid var(--border);
        padding: 13px 16px;
        white-space: nowrap;
    }

    .employers-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(14,32,57,.05);
        vertical-align: middle;
    }

    .employers-table tbody tr:last-child td { border-bottom: none; }

    .employers-table tbody tr {
        transition: background .15s;
    }

    .employers-table tbody tr:hover {
        background: rgba(212,148,58,.03);
    }

    /* Company cell */
    .company-logo {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--navy), var(--navy-light));
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .company-name {
        font-weight: 600;
        color: #1a2e45;
        font-size: 14px;
        line-height: 1.2;
    }

    .company-tin {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* Status badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .status-pill-approved { background: rgba(16,185,129,.1);  color: #065f46; }
    .status-pill-pending  { background: rgba(234,179,8,.12);  color: #854d0e; }
    .status-pill-rejected { background: rgba(239,68,68,.1);   color: #991b1b; }

    .status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-pill-approved .dot { background: #10b981; }
    .status-pill-pending  .dot { background: #f59e0b; }
    .status-pill-rejected .dot { background: #ef4444; }

    /* Record count badge */
    .record-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 24px;
        padding: 0 8px;
        background: rgba(14,32,57,.06);
        color: var(--navy);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        font-family: 'Sora', sans-serif;
    }

    /* Action buttons */
    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--navy-dark);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-view:hover {
        background: var(--navy);
        color: var(--gold);
        transform: translateY(-1px);
    }

    .btn-tbl-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: 8px;
        cursor: pointer;
        transition: background .15s, border-color .15s;
        flex-shrink: 0;
        padding: 0;
    }

    .btn-tbl-delete:hover {
        background: rgba(239,68,68,.09);
        border-color: #dc2626;
    }

    .td-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Empty state */
    .empty-state {
        padding: 64px 24px;
        text-align: center;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: rgba(14,32,57,.05);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: var(--text-muted);
        margin: 0 auto 16px;
    }

    /* Pagination override */
    .pagination .page-link {
        border-radius: 8px !important;
        border: 1px solid var(--border);
        color: var(--navy);
        font-size: 13px;
        padding: 6px 12px;
        margin: 0 2px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: var(--navy-dark);
        border-color: var(--navy-dark);
        color: #fff;
    }

    .pagination .page-link:hover {
        background: var(--gold-pale);
        border-color: var(--gold);
        color: var(--navy);
    }

    /* Delete modal */
    .emp-del-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(14,32,57,.45);
        backdrop-filter: blur(3px);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }

    .emp-del-overlay.active { display: flex; }

    .emp-del-modal {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 2rem 2rem 1.75rem;
        max-width: 430px;
        width: calc(100% - 2rem);
        box-shadow: 0 24px 64px rgba(14,32,57,.18);
        text-align: center;
        animation: emp-del-in .18s ease;
    }

    @keyframes emp-del-in {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .emp-del-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(239,68,68,.09);
        border: 1px solid rgba(239,68,68,.22);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #dc2626;
        font-size: 24px;
    }

    .emp-del-title {
        font-family: 'Sora', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a2e45;
        margin: 0 0 .55rem;
    }

    .emp-del-body {
        font-size: .875rem;
        color: var(--text-muted);
        line-height: 1.65;
        margin: 0 0 1.5rem;
    }

    .emp-del-body strong { color: #1a2e45; font-weight: 600; }

    .emp-del-actions {
        display: flex;
        gap: .65rem;
        justify-content: center;
    }

    .btn-emp-confirm-delete {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .55rem 1.4rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }

    .btn-emp-confirm-delete:hover { background: #b91c1c; }

    .btn-emp-cancel-delete {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-muted);
        border-radius: 10px;
        padding: .55rem 1.2rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        cursor: pointer;
        transition: border-color .15s, color .15s;
    }

    .btn-emp-cancel-delete:hover {
        border-color: #6b7280;
        color: #1a2e45;
    }

    @media (max-width: 767px) {
        .hide-mobile { display: none; }
        .filters-bar { gap: 8px; }
        .search-input-wrap { min-width: 100%; }
    }
</style>

<!-- PAGE HEADER -->
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 style="font-family:'Sora',sans-serif; font-weight:700; color:var(--navy); margin:0; font-size:20px;">
            Employers
        </h4>
        <p style="font-size:13px; color:var(--text-muted); margin:4px 0 0;">
            All registered employers and their verification status
        </p>
    </div>
    <div style="font-size:12px; color:var(--text-muted); padding-top:4px;">
        <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d M Y') }}
    </div>
</div>

<!-- STATS ROW -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="employers-stat-card">
            <div class="stat-icon stat-icon-total"><i class="bi bi-building"></i></div>
            <div>
                <div class="stat-value">{{ $employers->total() }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="employers-stat-card">
            <div class="stat-icon stat-icon-approved"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value" style="color:#059669;">{{ $employers->getCollection()->where('status','approved')->count() }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="employers-stat-card">
            <div class="stat-icon stat-icon-pending"><i class="bi bi-clock"></i></div>
            <div>
                <div class="stat-value" style="color:#b45309;">{{ $employers->getCollection()->where('status','pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="employers-stat-card">
            <div class="stat-icon stat-icon-rejected"><i class="bi bi-x-circle"></i></div>
            <div>
                <div class="stat-value" style="color:#dc2626;">{{ $employers->getCollection()->where('status','rejected')->count() }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>
</div>

<!-- FILTERS BAR -->
<div class="filters-bar mb-4">
    <div class="search-input-wrap">
        <i class="bi bi-search"></i>
        <input
            type="text"
            class="search-input"
            id="employerSearch"
            placeholder="Search by company, email, RDB or TIN…"
        >
    </div>
    <select class="filter-select" id="statusFilter">
        <option value="">All Statuses</option>
        <option value="approved">Approved</option>
        <option value="pending">Pending</option>
        <option value="rejected">Rejected</option>
    </select>
</div>

<!-- ALERTS -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TABLE CARD -->
<div class="table-card">

    <div class="table-card-header">
        <h6><i class="bi bi-building me-2"></i>Employer Registry</h6>
        <span style="font-size:12px; color:var(--text-muted);">
            {{ $employers->firstItem() }}–{{ $employers->lastItem() }} of {{ $employers->total() }}
        </span>
    </div>

    @if($employers->count())

    <div style="overflow-x:auto;">
        <table class="table employers-table mb-0" id="employersTable">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Company</th>
                    <th class="hide-mobile">Contact</th>
                    <th class="hide-mobile">RDB Number</th>
                    <th class="hide-mobile">TIN Number</th>
                    <th>Status</th>
                    <th class="hide-mobile">Records</th>
                    <th class="hide-mobile">Registered</th>
                    <th style="width:130px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employers as $employer)
                <tr data-name="{{ strtolower($employer->company_name) }}"
                    data-email="{{ strtolower($employer->email) }}"
                    data-rdb="{{ strtolower($employer->rdb_number) }}"
                    data-tin="{{ strtolower($employer->tin_number) }}"
                    data-status="{{ $employer->status }}">

                    <td style="color:var(--text-muted); font-size:12px;">
                        {{ $employers->firstItem() + $loop->index }}
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="company-logo">
                                {{ strtoupper(substr($employer->company_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="company-name">{{ $employer->company_name }}</div>
                                <div class="company-tin">{{ $employer->address ?? 'No address' }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="hide-mobile">
                        <div style="font-size:13px; color:#1a2e45;">{{ $employer->email }}</div>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                            <i class="bi bi-telephone me-1"></i>{{ $employer->phone ?? '—' }}
                        </div>
                    </td>

                    <td class="hide-mobile">
                        <span style="font-size:13px; font-weight:500; color:#1a2e45; font-family:'Sora',sans-serif;">
                            {{ $employer->rdb_number ?? '—' }}
                        </span>
                    </td>

                    <td class="hide-mobile">
                        <span style="font-size:13px; font-weight:500; color:#1a2e45; font-family:'Sora',sans-serif;">
                            {{ $employer->tin_number ?? '—' }}
                        </span>
                    </td>

                    <td>
                        @php $status = $employer->status; @endphp
                        <span class="status-pill status-pill-{{ $status }}">
                            <span class="dot"></span>
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    <td class="hide-mobile">
                        <span class="record-count">
                            {{ $employer->employmentRecords->count() }}
                        </span>
                    </td>

                    <td class="hide-mobile">
                        <span style="font-size:12px; color:var(--text-muted);">
                            {{ $employer->created_at->format('d M Y') }}
                        </span>
                    </td>

                    <td>
                        <div class="td-actions">
                            <a href="{{ route('admin.employers.show', $employer->id) }}" class="btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <button
                                type="button"
                                class="btn-tbl-delete"
                                title="Delete employer"
                                onclick="openEmpDeleteModal(
                                    {{ $employer->id }},
                                    '{{ addslashes($employer->company_name) }}'
                                )">
                                <i class="bi bi-trash3" style="font-size:13px;"></i>
                            </button>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex align-items-center justify-content-between px-4 py-3"
        style="border-top:1px solid var(--border); background:#fafbfc;">
        <span style="font-size:12px; color:var(--text-muted);">
            Showing {{ $employers->firstItem() }}–{{ $employers->lastItem() }} of {{ $employers->total() }} employers
        </span>
        <div>{{ $employers->links() }}</div>
    </div>

    @else

    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-building-x"></i></div>
        <h6 style="font-family:'Sora',sans-serif; font-weight:600; color:var(--navy); margin-bottom:6px;">
            No employers found
        </h6>
        <p style="font-size:13px; color:var(--text-muted); margin:0;">
            There are no registered employers in the system yet.
        </p>
    </div>

    @endif

</div>

<!-- DELETE MODAL -->
<div id="emp-delete-modal" class="emp-del-overlay" onclick="closeEmpDeleteModal(event)">
    <div class="emp-del-modal">
        <div class="emp-del-icon">
            <i class="bi bi-trash3-fill"></i>
        </div>
        <h3 class="emp-del-title">Delete Employer</h3>
        <p class="emp-del-body">
            You're about to permanently delete <strong id="emp-del-name"></strong> and all associated data.
            This action <strong>cannot be undone</strong>.
        </p>
        <div class="emp-del-actions">
            <form id="emp-delete-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-emp-confirm-delete">
                    <i class="bi bi-trash3 me-1"></i> Yes, Delete
                </button>
            </form>
            <button type="button" class="btn-emp-cancel-delete" onclick="closeEmpDeleteModal()">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    // Table filter
    const searchInput  = document.getElementById('employerSearch');
    const statusFilter = document.getElementById('statusFilter');
    const rows         = document.querySelectorAll('#employersTable tbody tr');

    function filterTable() {
        const q      = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value.toLowerCase();
        rows.forEach(row => {
            const matchSearch = !q ||
                (row.dataset.name  || '').includes(q) ||
                (row.dataset.email || '').includes(q) ||
                (row.dataset.rdb   || '').includes(q) ||
                (row.dataset.tin   || '').includes(q);
            const matchStatus = !status || row.dataset.status === status;
            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Delete modal
    function openEmpDeleteModal(id, name) {
        document.getElementById('emp-del-name').textContent = name;
        document.getElementById('emp-delete-form').action =
            '{{ url("admin/employers") }}/' + id;
        document.getElementById('emp-delete-modal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEmpDeleteModal(e) {
        if (e && e.target !== document.getElementById('emp-delete-modal')) return;
        document.getElementById('emp-delete-modal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEmpDeleteModal();
    });
</script>

@endsection