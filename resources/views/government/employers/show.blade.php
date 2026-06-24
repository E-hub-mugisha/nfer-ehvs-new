@extends('layouts.app')

@section('title', $employer->company_name)

@section('content')

<style>
    .detail-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(14, 32, 57, .06);
        overflow: hidden;
    }

    .detail-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafbfc;
    }

    .detail-card-header h6 {
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin: 0;
    }

    .detail-card-body { padding: 24px; }

    .employer-hero {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
        border-radius: 16px;
        padding: 28px 28px 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .employer-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(212, 148, 58, .1);
    }

    .employer-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 60px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(212, 148, 58, .06);
    }

    .employer-logo {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--navy-dark);
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(212, 148, 58, .4);
    }

    .employer-hero-name {
        font-family: 'Sora', sans-serif;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
    }

    .employer-hero-meta {
        font-size: 13px;
        color: var(--text-dim);
        margin-top: 4px;
    }

    .stat-pill {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 10px;
        padding: 10px 16px;
        text-align: center;
        min-width: 100px;
    }

    .stat-pill-value {
        font-family: 'Sora', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: var(--gold);
        line-height: 1;
    }

    .stat-pill-label {
        font-size: 11px;
        color: var(--text-dim);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .status-pending  { background: rgba(234, 179, 8, .12); color: #854d0e; }
    .status-approved { background: rgba(16, 185, 129, .12); color: #065f46; }
    .status-rejected { background: rgba(239, 68, 68, .1);  color: #991b1b; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-item label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 4px;
    }

    .info-item p {
        font-size: 14px;
        font-weight: 500;
        color: #1a2e45;
        margin: 0;
    }

    .btn-approve {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .22s;
        box-shadow: 0 3px 12px rgba(16, 185, 129, .3);
    }

    .btn-approve:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, .4);
    }

    .btn-reject {
        background: rgba(239, 68, 68, .08);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, .25);
        border-radius: 10px;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .22s;
    }

    .btn-reject:hover {
        background: rgba(239, 68, 68, .14);
        border-color: rgba(239, 68, 68, .4);
    }

    /* ── Edit button ── */
    .btn-edit {
        background: rgba(255, 255, 255, .1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 10px;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .22s;
    }

    .btn-edit:hover {
        background: rgba(255, 255, 255, .18);
        border-color: rgba(255, 255, 255, .4);
    }

    /* Edit button inside card header */
    .btn-edit-sm {
        background: rgba(212, 148, 58, .1);
        color: var(--gold, #d4943a);
        border: 1px solid rgba(212, 148, 58, .25);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .btn-edit-sm:hover {
        background: rgba(212, 148, 58, .18);
        color: var(--gold, #d4943a);
    }

    .rejection-notice {
        background: rgba(239, 68, 68, .06);
        border: 1px solid rgba(239, 68, 68, .2);
        border-left: 4px solid #ef4444;
        border-radius: 12px;
        padding: 16px 20px;
    }

    .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(14, 32, 57, .18);
    }

    .modal-header {
        padding: 22px 26px 0;
        border: none;
    }

    .modal-body  { padding: 20px 26px; }

    .modal-footer {
        padding: 0 26px 22px;
        border: none;
        gap: 10px;
    }

    /* ── Edit modal form styles ── */
    .edit-form-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted, #6b7280);
        display: block;
        margin-bottom: 5px;
    }

    .edit-form-control {
        width: 100%;
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 14px;
        font-family: 'DM Sans', sans-serif;
        color: #1a2e45;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }

    .edit-form-control:focus {
        border-color: var(--gold, #d4943a);
        box-shadow: 0 0 0 3px rgba(212, 148, 58, .12);
    }

    .edit-form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, .1);
    }

    .edit-form-group { margin-bottom: 18px; }

    .edit-section-title {
        font-family: 'Sora', sans-serif;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted, #6b7280);
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border, #e5e7eb);
        margin-bottom: 16px;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--gold, #d4943a), #c07d28);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 26px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .22s;
        box-shadow: 0 3px 12px rgba(212, 148, 58, .35);
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(212, 148, 58, .45);
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a2e45;
    }

    .form-control {
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        padding: 10px 14px;
        font-family: 'DM Sans', sans-serif;
        transition: border-color .2s;
        resize: vertical;
    }

    .form-control:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-pale);
        outline: none;
    }

    .records-table { font-size: 13px; }
    .records-table th { font-size: 10px !important; }

    .employee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--navy), var(--navy-light));
        color: var(--gold);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        font-family: 'Sora', sans-serif;
        flex-shrink: 0;
    }

    .emp-status-active     { background: rgba(16, 185, 129, .1); color: #065f46; }
    .emp-status-inactive   { background: rgba(107, 114, 128, .1); color: #374151; }
    .emp-status-terminated { background: rgba(239, 68, 68, .1);  color: #991b1b; }
    .emp-status-resigned   { background: rgba(234, 179, 8, .1);  color: #854d0e; }

    @media (max-width: 767px) {
        .info-grid { grid-template-columns: 1fr; gap: 14px; }
        .stat-pill { min-width: 80px; }
        .employer-hero-name { font-size: 18px; }
    }
</style>

<!-- BREADCRUMB -->
<div class="d-flex align-items-center gap-2 mb-4" style="font-size:13px; color:var(--text-muted);">
    <a href="/government/employers" style="color:var(--text-muted); text-decoration:none;">Employers</a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span style="color:#1a2e45; font-weight:500;">{{ $employer->company_name }}</span>
</div>

<!-- HERO -->
<div class="employer-hero mb-4">
    <div class="d-flex align-items-start gap-4 flex-wrap position-relative" style="z-index:1;">
        <div class="employer-logo">
            {{ strtoupper(substr($employer->company_name, 0, 1)) }}
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-3 flex-wrap mb-1">
                <div class="employer-hero-name">{{ $employer->company_name }}</div>
                <span class="status-badge status-{{ $employer->status }}">
                    @if($employer->status === 'approved') <i class="bi bi-check-circle-fill"></i>
                    @elseif($employer->status === 'rejected') <i class="bi bi-x-circle-fill"></i>
                    @else <i class="bi bi-clock-fill"></i>
                    @endif
                    {{ ucfirst($employer->status) }}
                </span>
            </div>
            <div class="employer-hero-meta">
                <i class="bi bi-envelope me-1"></i>{{ $employer->email }}
                &nbsp;&nbsp;
                <i class="bi bi-telephone me-1"></i>{{ $employer->phone }}
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <!-- Edit button in hero -->
            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="bi bi-pencil-square"></i> Edit
            </button>

            @if($employer->status !== 'approved')
            <form method="POST" action="{{ route('government.employers.approve', $employer) }}">
                @csrf
                <button type="submit" class="btn-approve">
                    <i class="bi bi-check-circle"></i> Approve
                </button>
            </form>
            @endif

            @if($employer->status !== 'rejected')
            <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle"></i> Reject
            </button>
            @endif

            <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>

    <div class="d-flex gap-3 mt-4 flex-wrap position-relative" style="z-index:1;">
        <div class="stat-pill">
            <div class="stat-pill-value">{{ $stats['total_records'] }}</div>
            <div class="stat-pill-label">Records</div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-value">{{ $stats['active_employees'] }}</div>
            <div class="stat-pill-label">Active</div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-value">{{ $stats['transfers_sent'] }}</div>
            <div class="stat-pill-label">Sent Transfers</div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill-value">{{ $stats['transfers_received'] }}</div>
            <div class="stat-pill-label">Received Transfers</div>
        </div>
    </div>
</div>

<!-- REJECTION NOTICE -->
@if($employer->status === 'rejected' && $employer->rejection_reason)
<div class="rejection-notice mb-4">
    <div class="d-flex align-items-center gap-2 mb-1">
        <i class="bi bi-exclamation-circle-fill text-danger"></i>
        <strong style="font-size:14px; color:#991b1b;">Rejection Reason</strong>
    </div>
    <p style="font-size:14px; color:#7f1d1d; margin:0;">{{ $employer->rejection_reason }}</p>
</div>
@endif

<div class="row g-4">

    <!-- LEFT COLUMN -->
    <div class="col-lg-4">

        <!-- Company Details -->
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6><i class="bi bi-building me-2"></i>Company Details</h6>
                <button type="button" class="btn-edit-sm" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
            <div class="detail-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>RDB Number</label>
                        <p>{{ $employer->rdb_number ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>TIN Number</label>
                        <p>{{ $employer->tin_number ?? '—' }}</p>
                    </div>
                    <div class="info-item" style="grid-column: span 2">
                        <label>Address</label>
                        <p>{{ $employer->address ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Registered</label>
                        <p>{{ $employer->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Last Updated</label>
                        <p>{{ $employer->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Owner -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h6><i class="bi bi-person me-2"></i>Account Owner</h6>
            </div>
            <div class="detail-card-body">
                @if($employer->user)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="employee-avatar" style="width:44px;height:44px;font-size:16px;border-radius:11px;">
                        {{ strtoupper(substr($employer->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600; font-size:14px; color:#1a2e45;">{{ $employer->user->name }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">{{ $employer->user->email }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <label>Account Created</label>
                    <p>{{ $employer->user->created_at->format('d M Y') }}</p>
                </div>
                @else
                <p style="font-size:14px; color:var(--text-muted); margin:0;">No linked account.</p>
                @endif
            </div>
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-8">

        <!-- Employment Records -->
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6><i class="bi bi-journal-text me-2"></i>Employment Records</h6>
                <span style="font-size:12px; color:var(--text-muted);">{{ $employer->employmentRecords->count() }} total</span>
            </div>

            @if($employer->employmentRecords->isEmpty())
            <div class="detail-card-body text-center py-5">
                <i class="bi bi-journal-x" style="font-size:36px; color:var(--border);"></i>
                <p style="font-size:14px; color:var(--text-muted); margin-top:10px;">No employment records yet.</p>
            </div>
            @else
            <div style="overflow-x:auto;">
                <table class="table records-table mb-0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employer->employmentRecords as $record)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($record->employee->first_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; color:#1a2e45; white-space:nowrap;">
                                            {{ $record->employee->full_name ?? '—' }}
                                        </div>
                                        <div style="font-size:11px; color:var(--text-muted);">
                                            {{ $record->employee->nid ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="white-space:nowrap;">{{ $record->job_title }}</td>
                            <td>{{ $record->department ?? '—' }}</td>
                            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}</td>
                            <td style="white-space:nowrap;">
                                {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('d M Y') : '—' }}
                            </td>
                            <td>
                                <span class="badge-status emp-status-{{ $record->employment_status }}">
                                    {{ ucfirst($record->employment_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Transfer Requests -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h6><i class="bi bi-arrow-left-right me-2"></i>Transfer Requests</h6>
                <span style="font-size:12px; color:var(--text-muted);">
                    {{ $employer->sentTransferRequests->count() + $employer->receivedTransferRequests->count() }} total
                </span>
            </div>

            @php
                $allTransfers = $employer->sentTransferRequests
                    ->each(fn($t) => $t->direction = 'sent')
                    ->concat(
                        $employer->receivedTransferRequests->each(fn($t) => $t->direction = 'received')
                    )
                    ->sortByDesc('created_at');
            @endphp

            @if($allTransfers->isEmpty())
            <div class="detail-card-body text-center py-5">
                <i class="bi bi-arrow-left-right" style="font-size:36px; color:var(--border);"></i>
                <p style="font-size:14px; color:var(--text-muted); margin-top:10px;">No transfer requests.</p>
            </div>
            @else
            <div style="overflow-x:auto;">
                <table class="table records-table mb-0">
                    <thead>
                        <tr>
                            <th>Direction</th>
                            <th>Employee</th>
                            <th>Proposed Title</th>
                            <th>Proposed Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTransfers as $tr)
                        <tr>
                            <td>
                                @if($tr->direction === 'sent')
                                <span class="badge-status" style="background:rgba(59,130,246,.1); color:#1e40af; white-space:nowrap;">
                                    <i class="bi bi-arrow-right me-1"></i> Sent
                                </span>
                                @else
                                <span class="badge-status" style="background:rgba(139,92,246,.1); color:#5b21b6; white-space:nowrap;">
                                    <i class="bi bi-arrow-left me-1"></i> Received
                                </span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">{{ $tr->employee->full_name ?? '—' }}</td>
                            <td>{{ $tr->proposed_job_title ?? '—' }}</td>
                            <td style="white-space:nowrap;">
                                {{ $tr->proposed_start_date ? $tr->proposed_start_date->format('d M Y') : '—' }}
                            </td>
                            <td>
                                <span class="badge-status
                                    @if($tr->status === 'approved') emp-status-active
                                    @elseif($tr->status === 'rejected') emp-status-terminated
                                    @else status-pending
                                    @endif">
                                    {{ ucfirst($tr->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════
     EDIT MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="editModalLabel"
                        style="font-family:'Sora',sans-serif; font-weight:700; color:#1a2e45; font-size:18px;">
                        Edit Employer Details
                    </h5>
                    <p style="font-size:13px; color:var(--text-muted); margin:4px 0 0;">
                        Changes will be saved immediately.
                    </p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('government.employers.update', $employer) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    {{-- Company identity --}}
                    <div class="edit-section-title"><i class="bi bi-building me-2"></i>Company Information</div>

                    <div class="row g-3 mb-2">
                        <div class="col-12">
                            <div class="edit-form-group">
                                <label class="edit-form-label" for="edit_company_name">Company Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="edit_company_name"
                                    name="company_name"
                                    class="edit-form-control @error('company_name') is-invalid @enderror"
                                    value="{{ old('company_name', $employer->company_name) }}"
                                    required>
                                @error('company_name')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="edit-form-group">
                                <label class="edit-form-label" for="edit_rdb_number">RDB Number</label>
                                <input
                                    type="text"
                                    id="edit_rdb_number"
                                    name="rdb_number"
                                    class="edit-form-control @error('rdb_number') is-invalid @enderror"
                                    value="{{ old('rdb_number', $employer->rdb_number) }}">
                                @error('rdb_number')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="edit-form-group">
                                <label class="edit-form-label" for="edit_tin_number">TIN Number</label>
                                <input
                                    type="text"
                                    id="edit_tin_number"
                                    name="tin_number"
                                    class="edit-form-control @error('tin_number') is-invalid @enderror"
                                    value="{{ old('tin_number', $employer->tin_number) }}">
                                @error('tin_number')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="edit-form-group mb-0">
                                <label class="edit-form-label" for="edit_address">Address</label>
                                <input
                                    type="text"
                                    id="edit_address"
                                    name="address"
                                    class="edit-form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', $employer->address) }}">
                                @error('address')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr style="border-color:var(--border); margin:22px 0;">

                    {{-- Contact --}}
                    <div class="edit-section-title"><i class="bi bi-envelope me-2"></i>Contact Information</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="edit-form-group mb-0">
                                <label class="edit-form-label" for="edit_email">Email Address <span class="text-danger">*</span></label>
                                <input
                                    type="email"
                                    id="edit_email"
                                    name="email"
                                    class="edit-form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $employer->email) }}"
                                    required>
                                @error('email')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="edit-form-group mb-0">
                                <label class="edit-form-label" for="edit_phone">Phone Number</label>
                                <input
                                    type="text"
                                    id="edit_phone"
                                    name="phone"
                                    class="edit-form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $employer->phone) }}">
                                @error('phone')
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                        style="border-radius:10px; font-weight:500;">
                        Cancel
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check2-circle"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     REJECT MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="rejectModalLabel"
                        style="font-family:'Sora',sans-serif; font-weight:700; color:#1a2e45; font-size:18px;">
                        Reject Employer
                    </h5>
                    <p style="font-size:13px; color:var(--text-muted); margin:4px 0 0;">
                        Provide a reason — the employer may be notified.
                    </p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('government.employers.reject', $employer) }}">
                @csrf
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 p-3 mb-4"
                        style="background:rgba(239,68,68,.06); border:1px solid rgba(239,68,68,.2); border-radius:12px;">
                        <div class="employer-logo" style="width:46px;height:46px;font-size:18px;border-radius:12px;flex-shrink:0;">
                            {{ strtoupper(substr($employer->company_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:14px; color:#1a2e45;">{{ $employer->company_name }}</div>
                            <div style="font-size:12px; color:var(--text-muted);">{{ $employer->email }}</div>
                        </div>
                    </div>

                    <label class="form-label" for="reason">
                        Rejection Reason <span class="text-danger">*</span>
                    </label>
                    <textarea
                        name="reason"
                        id="reason"
                        rows="4"
                        class="form-control @error('reason') is-invalid @enderror"
                        placeholder="e.g. Incomplete RDB registration documents, unable to verify TIN number..."
                        required>{{ old('reason') }}</textarea>

                    @error('reason')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                        style="border-radius:10px; font-weight:500;">
                        Cancel
                    </button>
                    <button type="submit" class="btn-reject px-4">
                        <i class="bi bi-x-circle"></i> Confirm Rejection
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="deleteModalLabel"
                        style="font-family:'Sora',sans-serif; font-weight:700; color:#1a2e45; font-size:18px;">
                        Delete Employer
                    </h5>
                    <p style="font-size:13px; color:var(--text-muted); margin:4px 0 0;">
                        This action cannot be undone.
                    </p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('government.employers.destroy', $employer) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 p-3 mb-2"
                        style="background:rgba(239,68,68,.06); border:1px solid rgba(239,68,68,.2); border-radius:12px;">
                        <div class="employer-logo" style="width:46px;height:46px;font-size:18px;border-radius:12px;flex-shrink:0;">
                            {{ strtoupper(substr($employer->company_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:14px; color:#1a2e45;">{{ $employer->company_name }}</div>
                            <div style="font-size:12px; color:var(--text-muted);">{{ $employer->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                        style="border-radius:10px; font-weight:500;">
                        Cancel
                    </button>
                    <button type="submit" class="btn-reject px-4">
                        <i class="bi bi-trash"></i> Confirm Deletion
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@if($errors->has('reason'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    });
</script>
@endif

{{-- Re-open edit modal on validation error --}}
@if($errors->hasAny(['company_name', 'email', 'phone', 'rdb_number', 'tin_number', 'address']))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
</script>
@endif

@endsection