{{-- resources/views/government/transfers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Transfer Requests — NFER-EHVS')

@section('content')

<style>
    :root {
        --navy:       #0d1b3e;
        --navy-mid:   #162552;
        --navy-light: #1e3370;
        --gold:       #c9a84c;
        --gold-light: #e8c878;
        --gold-pale:  rgba(201,168,76,.1);
        --surface:    #f5f6fa;
        --card:       #ffffff;
        --border:     #e4e7f0;
        --text:       #1a2340;
        --muted:      #6b7a9e;
        --success:    #16a34a;
        --warning:    #d97706;
        --danger:     #dc2626;
        --info:       #0369a1;
        --radius:     14px;
        --shadow:     0 2px 16px rgba(13,27,62,.07);
        --shadow-md:  0 4px 28px rgba(13,27,62,.13);
    }
    body { background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }

    /* Page header */
    .page-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        border-radius: var(--radius);
        padding: 26px 30px;
        margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .page-header::before {
        content:''; position:absolute; inset:0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M20 0L40 20L20 40L0 20z'/%3E%3C/g%3E%3C/svg%3E");
    }
    .page-header h1 { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 4px; }
    .page-header p  { color:rgba(255,255,255,.6); font-size:.85rem; margin:0; }

    /* Status tab pills */
    .status-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
    .status-tab {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 16px; border-radius:20px; font-size:.8rem; font-weight:700;
        text-decoration:none; transition:all .2s;
        border: 1.5px solid var(--border);
        background: var(--card); color: var(--muted);
    }
    .status-tab:hover  { border-color: var(--navy); color: var(--navy); }
    .status-tab.active { background: var(--navy); color: #fff; border-color: var(--navy); }
    .status-tab .cnt   { background:rgba(255,255,255,.25); border-radius:10px; padding:1px 7px; font-size:.73rem; }
    .status-tab:not(.active) .cnt { background: #eef0f8; color: var(--navy); }

    /* Filter bar */
    .filter-bar {
        background: var(--card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 18px 22px;
        margin-bottom: 20px; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;
        box-shadow: var(--shadow);
    }
    .filter-group { display:flex; flex-direction:column; gap:5px; }
    .filter-group label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); }
    .filter-ctrl {
        padding:8px 12px; border-radius:8px; border:1.5px solid var(--border);
        font-size:.84rem; font-family:inherit; color:var(--text); background:#fff;
        outline:none; transition:border-color .2s;
    }
    .filter-ctrl:focus { border-color: var(--gold); }
    .btn-filter {
        padding:8px 20px; border-radius:8px; border:none;
        background: var(--navy); color:#fff; font-size:.84rem; font-weight:700;
        cursor:pointer; transition:background .2s; align-self:flex-end;
    }
    .btn-filter:hover { background: var(--navy-light); }
    .btn-reset {
        padding:8px 16px; border-radius:8px; border:1.5px solid var(--border);
        background:#fff; color:var(--muted); font-size:.84rem; font-weight:600;
        cursor:pointer; transition:all .2s; align-self:flex-end; text-decoration:none;
        display:inline-flex; align-items:center;
    }
    .btn-reset:hover { border-color:var(--navy); color:var(--navy); }

    /* Table card */
    .table-card {
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden;
    }
    .table-card-header {
        padding:16px 22px; border-bottom:1px solid var(--border);
        display:flex; align-items:center; justify-content:space-between;
    }
    .table-card-header .title { font-family:'Syne',sans-serif; font-size:.95rem; font-weight:700; color:var(--text); }
    .table-card-header .count-badge {
        background:var(--gold-pale); border:1px solid rgba(201,168,76,.3);
        color:#92700a; border-radius:20px; padding:3px 12px; font-size:.78rem; font-weight:700;
    }

    .dash-table { width:100%; border-collapse:collapse; }
    .dash-table thead th {
        padding:10px 14px; font-size:.71rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.5px; color:var(--muted);
        background:#fafbfd; border-bottom:1px solid var(--border); white-space:nowrap;
    }
    .dash-table tbody td { padding:13px 14px; font-size:.84rem; border-bottom:1px solid #f0f2f8; vertical-align:middle; }
    .dash-table tbody tr:last-child td { border-bottom:none; }
    .dash-table tbody tr:hover td { background:#fafbfd; }
    .table-scroll { overflow-x:auto; }

    /* Avatar */
    .emp-cell { display:flex; align-items:center; gap:10px; }
    .emp-avatar {
        width:36px; height:36px; border-radius:50%; flex-shrink:0;
        background:var(--navy); color:#fff;
        display:flex; align-items:center; justify-content:center;
        font-size:.75rem; font-weight:700; overflow:hidden;
    }
    .emp-avatar img { width:100%; height:100%; object-fit:cover; }
    .emp-name { font-weight:700; font-size:.85rem; color:var(--text); }
    .emp-nid  { font-size:.73rem; color:var(--muted); }

    /* Status pills */
    .pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.71rem; font-weight:700; text-transform:uppercase; letter-spacing:.3px; white-space:nowrap; }
    .pill-pending  { background:rgba(217,119,6,.1);  color:#b45309; }
    .pill-approved { background:rgba(22,163,74,.1);  color:#15803d; }
    .pill-rejected { background:rgba(220,38,38,.1);  color:#b91c1c; }

    /* Action buttons */
    .btn-approve {
        padding:5px 13px; border-radius:7px; font-size:.78rem; font-weight:700;
        background:rgba(22,163,74,.1); color:#15803d; border:1.5px solid rgba(22,163,74,.3);
        cursor:pointer; transition:all .2s;
    }
    .btn-approve:hover { background:rgba(22,163,74,.2); }
    .btn-reject  {
        padding:5px 13px; border-radius:7px; font-size:.78rem; font-weight:700;
        background:rgba(220,38,38,.08); color:#b91c1c; border:1.5px solid rgba(220,38,38,.25);
        cursor:pointer; transition:all .2s; margin-left:6px;
    }
    .btn-reject:hover { background:rgba(220,38,38,.15); }
    .btn-view {
        padding:5px 11px; border-radius:7px; font-size:.78rem; font-weight:700;
        background:var(--gold-pale); color:#92700a; border:1.5px solid rgba(201,168,76,.3);
        cursor:pointer; transition:all .2s; margin-left:6px;
    }
    .btn-view:hover { background:rgba(201,168,76,.2); }

    /* Modal */
    .modal-overlay {
        display:none; position:fixed; inset:0; background:rgba(13,27,62,.55);
        backdrop-filter:blur(4px); z-index:1000;
        align-items:center; justify-content:center; padding:20px;
    }
    .modal-overlay.open { display:flex; }
    .modal-box {
        background:#fff; border-radius:var(--radius); box-shadow:var(--shadow-md);
        width:100%; max-width:540px; max-height:90vh; overflow-y:auto;
        animation: slideUp .25s ease;
    }
    @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .modal-header {
        padding:20px 24px; border-bottom:1px solid var(--border);
        display:flex; align-items:center; justify-content:space-between;
    }
    .modal-header h5 { font-family:'Syne',sans-serif; font-size:1rem; font-weight:700; margin:0; color:var(--text); }
    .modal-close { background:none; border:none; font-size:1.2rem; color:var(--muted); cursor:pointer; padding:4px 8px; line-height:1; border-radius:6px; }
    .modal-close:hover { background:#f0f2f8; color:var(--text); }
    .modal-body  { padding:24px; }
    .modal-footer{ padding:16px 24px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end; }

    /* Detail row */
    .detail-row { display:flex; gap:8px; margin-bottom:12px; }
    .detail-label { font-size:.75rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; min-width:130px; padding-top:2px; }
    .detail-value { font-size:.87rem; color:var(--text); flex:1; }
    .detail-section { font-family:'Syne',sans-serif; font-size:.82rem; font-weight:700; color:var(--navy); text-transform:uppercase; letter-spacing:.5px; margin:18px 0 10px; padding-bottom:6px; border-bottom:2px solid var(--gold-pale); }

    .reject-textarea {
        width:100%; padding:10px 14px; border:1.5px solid var(--border);
        border-radius:8px; font-size:.87rem; font-family:inherit; resize:vertical;
        min-height:100px; outline:none; transition:border-color .2s; color:var(--text);
    }
    .reject-textarea:focus { border-color:var(--danger); }

    .btn-primary {
        padding:9px 22px; border-radius:8px; border:none;
        background:var(--navy); color:#fff; font-size:.87rem; font-weight:700; cursor:pointer; transition:background .2s;
    }
    .btn-primary:hover { background:var(--navy-light); }
    .btn-danger-full {
        padding:9px 22px; border-radius:8px; border:none;
        background:var(--danger); color:#fff; font-size:.87rem; font-weight:700; cursor:pointer; transition:opacity .2s;
    }
    .btn-danger-full:hover { opacity:.85; }
    .btn-secondary {
        padding:9px 18px; border-radius:8px; border:1.5px solid var(--border);
        background:#fff; color:var(--muted); font-size:.87rem; font-weight:600; cursor:pointer;
    }

    /* Pagination */
    .pagination-wrap { padding:16px 22px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
    .pagination-info { font-size:.8rem; color:var(--muted); }

    /* Alerts */
    .alert { padding:12px 18px; border-radius:10px; font-size:.85rem; font-weight:600; margin-bottom:18px; display:flex; align-items:center; gap:10px; }
    .alert-success { background:rgba(22,163,74,.1); color:#15803d; border:1px solid rgba(22,163,74,.2); }
    .alert-error   { background:rgba(220,38,38,.08); color:#b91c1c; border:1px solid rgba(220,38,38,.2); }

    /* Fade in */
    .fade-up { opacity:0; transform:translateY(14px); animation:fadeUp .4s ease forwards; }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
    .d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s}

    /* Empty state */
    .empty-state { text-align:center; padding:60px 20px; color:var(--muted); }
    .empty-state i { font-size:2.5rem; margin-bottom:16px; opacity:.4; }
    .empty-state p { font-size:.9rem; margin:0; }
</style>

<div class="container-fluid px-4 py-4">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success fade-up"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error fade-up"><i class="fas fa-times-circle"></i>{{ session('error') }}</div>
    @endif

    {{-- Page Header --}}
    <div class="page-header fade-up">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-exchange-alt me-2" style="color:var(--gold-light)"></i>Transfer Requests</h1>
                <p>Manage employee transfer requests between employers</p>
            </div>
            <div style="color:rgba(255,255,255,.55);font-size:.8rem;margin-top:6px;">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="status-tabs fade-up d1">
        @php
            $allCount      = array_sum($counts);
            $currentStatus = request('status', '');
        @endphp
        <a href="{{ request()->fullUrlWithQuery(['status' => '', 'page' => 1]) }}"
           class="status-tab {{ $currentStatus === '' ? 'active' : '' }}">
            All <span class="cnt">{{ $allCount }}</span>
        </a>
        @foreach(['pending' => '⏳', 'approved' => '✓', 'rejected' => '✕'] as $s => $icon)
        <a href="{{ request()->fullUrlWithQuery(['status' => $s, 'page' => 1]) }}"
           class="status-tab {{ $currentStatus === $s ? 'active' : '' }}">
            {{ ucfirst($s) }} <span class="cnt">{{ $counts[$s] ?? 0 }}</span>
        </a>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('government.transfer-requests.index') }}" class="filter-bar fade-up d2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="filter-group" style="flex:1;min-width:200px;">
            <label>Search Employee</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or NID…" class="filter-ctrl">
        </div>
        <div class="filter-group">
            <label>From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="filter-ctrl">
        </div>
        <div class="filter-group">
            <label>To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="filter-ctrl">
        </div>
        <button type="submit" class="btn-filter"><i class="fas fa-search me-1"></i>Filter</button>
        <a href="{{ route('government.transfer-requests.index') }}" class="btn-reset"><i class="fas fa-times me-1"></i>Reset</a>
    </form>

    {{-- Table --}}
    <div class="table-card fade-up d3">
        <div class="table-card-header">
            <span class="title">Transfer Requests</span>
            <span class="count-badge">{{ $transfers->total() }} records</span>
        </div>
        <div class="table-scroll">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Current Employer</th>
                        <th>Requesting Employer</th>
                        <th>Proposed Role</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $tr)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $tr->id }}</td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar">
                                    @if($tr->employee?->photo)
                                        <img src="{{ asset('storage/' . $tr->employee->photo) }}" alt="">
                                    @else
                                        {{ strtoupper(substr($tr->employee?->first_name ?? 'N', 0, 1) . substr($tr->employee?->last_name ?? 'A', 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="emp-name">{{ $tr->employee?->full_name ?? '—' }}</div>
                                    <div class="emp-nid">{{ $tr->employee?->nid ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.84rem;">{{ Str::limit($tr->currentEmployer?->company_name ?? '—', 28) }}</div>
                            <div style="font-size:.73rem;color:var(--muted);">{{ $tr->currentEmploymentRecord?->job_title ?? '' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.84rem;">{{ Str::limit($tr->requestingEmployer?->company_name ?? '—', 28) }}</div>
                        </td>
                        <td>
                            <div style="font-size:.84rem;">{{ $tr->proposed_job_title ?? '—' }}</div>
                            <div style="font-size:.73rem;color:var(--muted);">{{ $tr->proposed_department ?? '' }}</div>
                        </td>
                        <td style="font-size:.84rem;color:var(--muted);">
                            {{ $tr->proposed_start_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td><span class="pill pill-{{ strtolower($tr->status) }}">{{ $tr->status }}</span></td>
                        <td style="font-size:.78rem;color:var(--muted);white-space:nowrap;">
                            {{ $tr->created_at->format('d M Y') }}<br>
                            <span style="font-size:.72rem;">{{ $tr->created_at->format('H:i') }}</span>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <button class="btn-view" onclick="viewTransfer({{ $tr->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($tr->isPending())
                            <form method="POST" action="{{ route('government.transfer-requests.approve', $tr) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-approve"
                                    onclick="return confirm('Approve this transfer request? This will update employment records.')">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                            </form>
                            <button class="btn-reject" onclick="openReject({{ $tr->id }})">
                                <i class="fas fa-times me-1"></i>Reject
                            </button>
                            @elseif($tr->status === 'rejected' && $tr->rejection_reason)
                            <button class="btn-view" onclick="viewReason('{{ addslashes($tr->rejection_reason) }}')">
                                <i class="fas fa-comment-alt me-1"></i>Reason
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-exchange-alt"></i>
                                <p>No transfer requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            <span class="pagination-info">
                Showing {{ $transfers->firstItem() ?? 0 }}–{{ $transfers->lastItem() ?? 0 }} of {{ $transfers->total() }} records
            </span>
            {{ $transfers->links() }}
        </div>
    </div>

</div>

{{-- ── View Detail Modal ────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="viewModal">
    <div class="modal-box">
        <div class="modal-header">
            <h5><i class="fas fa-exchange-alt me-2" style="color:var(--gold)"></i>Transfer Request Details</h5>
            <button class="modal-close" onclick="closeModal('viewModal')">✕</button>
        </div>
        <div class="modal-body" id="viewModalBody">
            <div style="text-align:center;padding:30px;color:var(--muted);">
                <i class="fas fa-spinner fa-spin"></i> Loading…
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>

{{-- ── Reject Modal ─────────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <div class="modal-header">
            <h5 style="color:var(--danger);"><i class="fas fa-times-circle me-2"></i>Reject Transfer Request</h5>
            <button class="modal-close" onclick="closeModal('rejectModal')">✕</button>
        </div>
        <div class="modal-body">
            <p style="font-size:.87rem;color:var(--muted);margin-bottom:16px;">
                Please provide a clear reason for rejection. This will be visible to the employer.
            </p>
            <form id="rejectForm" method="POST">
                @csrf @method('PATCH')
                <textarea name="rejection_reason" class="reject-textarea" placeholder="Enter rejection reason…" required></textarea>
                <div class="modal-footer" style="padding:16px 0 0;border:none;">
                    <button type="button" class="btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                    <button type="submit" class="btn-danger-full"><i class="fas fa-times me-1"></i>Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Reason Modal ─────────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="reasonModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h5>Rejection Reason</h5>
            <button class="modal-close" onclick="closeModal('reasonModal')">✕</button>
        </div>
        <div class="modal-body">
            <p id="reasonText" style="font-size:.9rem;color:var(--text);line-height:1.6;margin:0;"></p>
        </div>
        <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('reasonModal')">Close</button></div>
    </div>
</div>


<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
});

// Reject modal
function openReject(id) {
    document.getElementById('rejectForm').action = `/government/transfer-requests/${id}/reject`;
    document.querySelector('#rejectForm textarea').value = '';
    openModal('rejectModal');
}

// Reason modal
function viewReason(text) {
    document.getElementById('reasonText').textContent = text;
    openModal('reasonModal');
}

// View detail modal
async function viewTransfer(id) {
    openModal('viewModal');
    document.getElementById('viewModalBody').innerHTML = '<div style="text-align:center;padding:30px;color:var(--muted);"><i class="fas fa-spinner fa-spin"></i> Loading…</div>';
    try {
        const res  = await fetch(`/government/transfer-requests/${id}`);
        const data = await res.json();
        const fmt  = v => v ?? '<span style="color:var(--muted)">—</span>';
        const fmtDate = v => v ? new Date(v).toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}) : '—';

        document.getElementById('viewModalBody').innerHTML = `
            <div class="detail-section">Employee</div>
            <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value">${fmt(data.employee?.first_name + ' ' + data.employee?.last_name)}</span></div>
            <div class="detail-row"><span class="detail-label">NID</span><span class="detail-value">${fmt(data.employee?.nid)}</span></div>

            <div class="detail-section">Transfer Details</div>
            <div class="detail-row"><span class="detail-label">From Employer</span><span class="detail-value">${fmt(data.current_employer?.company_name)}</span></div>
            <div class="detail-row"><span class="detail-label">To Employer</span><span class="detail-value">${fmt(data.requesting_employer?.company_name)}</span></div>
            <div class="detail-row"><span class="detail-label">Proposed Title</span><span class="detail-value">${fmt(data.proposed_job_title)}</span></div>
            <div class="detail-row"><span class="detail-label">Department</span><span class="detail-value">${fmt(data.proposed_department)}</span></div>
            <div class="detail-row"><span class="detail-label">Start Date</span><span class="detail-value">${fmtDate(data.proposed_start_date)}</span></div>

            <div class="detail-section">Reason & Status</div>
            <div class="detail-row"><span class="detail-label">Reason</span><span class="detail-value">${fmt(data.reason)}</span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="pill pill-${data.status}">${data.status}</span></span></div>
            ${data.rejection_reason ? `<div class="detail-row"><span class="detail-label">Rej. Reason</span><span class="detail-value" style="color:var(--danger)">${data.rejection_reason}</span></div>` : ''}
            <div class="detail-row"><span class="detail-label">Responded At</span><span class="detail-value">${fmtDate(data.responded_at)}</span></div>
            <div class="detail-row"><span class="detail-label">Submitted</span><span class="detail-value">${fmtDate(data.created_at)}</span></div>
        `;
    } catch {
        document.getElementById('viewModalBody').innerHTML = '<p style="color:var(--danger);text-align:center;">Failed to load data.</p>';
    }
}
</script>
@endsection