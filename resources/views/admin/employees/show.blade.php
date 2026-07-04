@extends('layouts.app')

@section('title', $employee->full_name)

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

    .detail-card-body {
        padding: 24px;
    }

    /* Hero */
    .emp-hero {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
        border-radius: 16px;
        padding: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .emp-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(212, 148, 58, .1);
    }

    .emp-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 60px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(212, 148, 58, .06);
    }

    .hero-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 30px;
        flex-shrink: 0;
        overflow: hidden;
        border: 3px solid rgba(212, 148, 58, .5);
        box-shadow: 0 4px 16px rgba(0, 0, 0, .2);
    }

    .hero-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-avatar-male {
        background: rgba(59, 130, 246, .25);
        color: #93c5fd;
    }

    .hero-avatar-female {
        background: rgba(236, 72, 153, .25);
        color: #f9a8d4;
    }

    .hero-name {
        font-family: 'Sora', sans-serif;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
    }

    .hero-meta {
        font-size: 13px;
        color: var(--text-dim);
        margin-top: 4px;
    }

    .hero-stat-pill {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 10px;
        padding: 10px 16px;
        text-align: center;
        min-width: 90px;
    }

    .hero-stat-value {
        font-family: 'Sora', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--gold);
        line-height: 1;
    }

    .hero-stat-label {
        font-size: 10px;
        color: var(--text-dim);
        margin-top: 3px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* Info grid */
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

    /* Gender pill */
    .gender-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .gp-male {
        background: rgba(59, 130, 246, .1);
        color: #1d4ed8;
    }

    .gp-female {
        background: rgba(236, 72, 153, .1);
        color: #be185d;
    }

    /* NID */
    .nid-code {
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy);
        background: rgba(14, 32, 57, .06);
        padding: 4px 10px;
        border-radius: 7px;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    /* Timeline */
    .timeline-item {
        display: flex;
        gap: 16px;
        margin-bottom: 0;
    }

    .timeline-line {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    .timeline-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--gold);
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px var(--gold);
        margin-top: 4px;
        flex-shrink: 0;
    }

    .timeline-dot-inactive {
        background: var(--text-muted);
        box-shadow: 0 0 0 2px var(--text-muted);
    }

    .timeline-connector {
        width: 2px;
        flex: 1;
        background: var(--border);
        margin: 6px 0;
        min-height: 20px;
    }

    .timeline-content {
        flex: 1;
        padding-bottom: 24px;
    }

    .timeline-record-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(14, 32, 57, .05);
    }

    .timeline-record-card.current {
        border-color: rgba(212, 148, 58, .35);
        box-shadow: 0 2px 12px rgba(212, 148, 58, .12);
    }

    .record-card-header {
        padding: 14px 18px;
        background: #fafbfc;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .record-card-header.current {
        background: rgba(212, 148, 58, .06);
    }

    .record-card-body {
        padding: 16px 18px;
    }

    .emp-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .esp-active {
        background: rgba(16, 185, 129, .1);
        color: #065f46;
    }

    .esp-inactive {
        background: rgba(107, 114, 128, .1);
        color: #374151;
    }

    .esp-terminated {
        background: rgba(239, 68, 68, .1);
        color: #991b1b;
    }

    .esp-resigned {
        background: rgba(234, 179, 8, .1);
        color: #854d0e;
    }

    .record-meta-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
    }

    .record-meta-item label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 2px;
    }

    .record-meta-item p {
        font-size: 13px;
        font-weight: 500;
        color: #1a2e45;
        margin: 0;
    }

    /* Transfer + Dispute tables */
    .sub-table {
        font-size: 13px;
    }

    .sub-table thead th {
        font-family: 'Sora', sans-serif;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--text-muted);
        background: #f8fafc;
        border-bottom: 2px solid var(--border);
        padding: 11px 14px;
        white-space: nowrap;
    }

    .sub-table tbody td {
        padding: 11px 14px;
        border-bottom: 1px solid rgba(14, 32, 57, .05);
        vertical-align: middle;
    }

    .sub-table tbody tr:last-child td {
        border-bottom: none;
    }

    .sub-table tbody tr:hover {
        background: rgba(212, 148, 58, .03);
    }

    .tr-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .tr-pending {
        background: rgba(234, 179, 8, .12);
        color: #854d0e;
    }

    .tr-approved {
        background: rgba(16, 185, 129, .1);
        color: #065f46;
    }

    .tr-rejected {
        background: rgba(239, 68, 68, .1);
        color: #991b1b;
    }

    .dispute-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .dp-open {
        background: rgba(239, 68, 68, .1);
        color: #991b1b;
    }

    .dp-resolved {
        background: rgba(16, 185, 129, .1);
        color: #065f46;
    }

    .dp-pending {
        background: rgba(234, 179, 8, .12);
        color: #854d0e;
    }

    .empty-sub {
        padding: 40px 24px;
        text-align: center;
    }

    .empty-sub i {
        font-size: 32px;
        color: var(--border);
        display: block;
        margin-bottom: 10px;
    }

    @media (max-width: 767px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .record-meta-row {
            grid-template-columns: 1fr;
        }

        .hero-name {
            font-size: 18px;
        }

        .hero-stat-pill {
            min-width: 70px;
        }
    }
</style>

<!-- BREADCRUMB -->
<div class="d-flex align-items-center gap-2 mb-4" style="font-size:13px; color:var(--text-muted);">
    <a href="{{ route('admin.employees.index') }}" style="color:var(--text-muted); text-decoration:none;">
        Employees
    </a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span style="color:#1a2e45; font-weight:500;">{{ $employee->full_name }}</span>

</div>

<!-- HERO -->
<div class="emp-hero mb-4">
    <div class="d-flex align-items-start gap-4 flex-wrap position-relative" style="z-index:1;">

        @if($employee->photo)
        <div class="hero-avatar">
            <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}">
        </div>
        @else
        <div class="hero-avatar {{ $employee->gender === 'Female' ? 'hero-avatar-female' : 'hero-avatar-male' }}">
            {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
        </div>
        @endif

        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-3 flex-wrap mb-1">
                <div class="hero-name">{{ $employee->full_name }}</div>
                <span class="gender-pill {{ $employee->gender === 'Female' ? 'gp-female' : 'gp-male' }}">
                    <i class="bi bi-{{ $employee->gender === 'Female' ? 'gender-female' : 'gender-male' }}"></i>
                    {{ $employee->gender }}
                </span>
            </div>
            <div class="hero-meta">
                @if($employee->email)
                <i class="bi bi-envelope me-1"></i>{{ $employee->email }}
                &nbsp;&nbsp;
                @endif
                @if($employee->phone)
                <i class="bi bi-telephone me-1"></i>{{ $employee->phone }}
                @endif
            </div>
            <div class="hero-meta mt-1">
                <i class="bi bi-geo-alt me-1"></i>{{ $employee->district ?? '—' }}
                @if($employee->sector), {{ $employee->sector }}@endif
            </div>

            <div class="d-flex gap-3 mt-3 flex-wrap position-relative" style="z-index:1;">
                <button type="button"
                    class="btn btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteEmployeeModal"
                    style="background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);
                   border-radius:9px;padding:7px 16px;font-size:13px;
                   font-weight:600;color:#fca5a5;">
                    <i class="bi bi-trash3 me-1"></i>Delete Employee
                </button>
                <button type="button"
                    class="btn btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editEmployeeModal"
                    style="background:rgba(212,148,58,.18);border:1px solid rgba(212,148,58,.35);
   border-radius:9px;padding:7px 16px;font-size:13px;
   font-weight:600;color:var(--gold);">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="d-flex gap-3 mt-4 flex-wrap position-relative" style="z-index:1;">
        <div class="hero-stat-pill">
            <div class="hero-stat-value">{{ $employee->employmentRecords->count() }}</div>
            <div class="hero-stat-label">Jobs</div>
        </div>
        <div class="hero-stat-pill">
            <div class="hero-stat-value">{{ $employee->transferRequests->count() }}</div>
            <div class="hero-stat-label">Transfers</div>
        </div>

        <div class="hero-stat-pill">
            <div class="hero-stat-value">
                {{ \Carbon\Carbon::parse($employee->dob)->age }}
            </div>
            <div class="hero-stat-label">Age</div>
        </div>
    </div>
</div>

<div class="row g-4">

    <!-- LEFT COLUMN -->
    <div class="col-lg-4">

        <!-- Personal Info -->
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6><i class="bi bi-person me-2"></i>Personal Details</h6>
            </div>
            <div class="detail-card-body">
                <div class="info-grid">
                    <div class="info-item" style="grid-column: span 2">
                        <label>National ID (NID)</label>
                        <span class="nid-code">{{ $employee->nid }}</span>
                    </div>
                    <div class="info-item">
                        <label>Date of Birth</label>
                        <p>{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Age</label>
                        <p>{{ \Carbon\Carbon::parse($employee->dob)->age }} years</p>
                    </div>
                    <div class="info-item">
                        <label>Gender</label>
                        <p>
                            <span class="gender-pill {{ $employee->gender === 'Female' ? 'gp-female' : 'gp-male' }}">
                                {{ $employee->gender }}
                            </span>
                        </p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>{{ $employee->phone ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>District</label>
                        <p>{{ $employee->district ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Sector</label>
                        <p>{{ $employee->sector ?? '—' }}</p>
                    </div>
                    <div class="info-item" style="grid-column: span 2">
                        <label>Email</label>
                        <p>{{ $employee->email ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Registered</label>
                        <p>{{ $employee->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account -->
        @if($employee->user)
        <div class="detail-card">
            <div class="detail-card-header">
                <h6><i class="bi bi-shield-person me-2"></i>System Account</h6>
            </div>
            <div class="detail-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:11px;
                                background:linear-gradient(135deg,var(--navy),var(--navy-light));
                                color:var(--gold);display:flex;align-items:center;
                                justify-content:center;font-weight:700;font-size:16px;
                                font-family:'Sora',sans-serif;flex-shrink:0;">
                        {{ strtoupper(substr($employee->user->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px;color:#1a2e45;">
                            {{ $employee->user->name }}
                        </div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            {{ $employee->user->email }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <label>Account Created</label>
                    <p>{{ $employee->user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-8">

        <!-- Employment History Timeline -->
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6><i class="bi bi-briefcase me-2"></i>Employment History</h6>
                <span style="font-size:12px;color:var(--text-muted);">
                    {{ $employee->employmentRecords->count() }} record(s)
                </span>
            </div>

            @if($employee->employmentRecords->isEmpty())
            <div class="empty-sub">
                <i class="bi bi-briefcase-fill"></i>
                <p style="font-size:14px;color:var(--text-muted);margin:0;">No employment records found.</p>
            </div>
            @else
            <div class="detail-card-body">
                @foreach($employee->employmentRecords->sortByDesc('start_date') as $record)
                @php $isCurrent = $record->employment_status === 'active'; @endphp
                <div class="timeline-item">
                    <div class="timeline-line">
                        <div class="timeline-dot {{ $isCurrent ? '' : 'timeline-dot-inactive' }}"></div>
                        @if(!$loop->last)
                        <div class="timeline-connector"></div>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-record-card {{ $isCurrent ? 'current' : '' }}">
                            <div class="record-card-header {{ $isCurrent ? 'current' : '' }}">
                                <div>
                                    <div style="font-family:'Sora',sans-serif;font-weight:700;font-size:15px;color:#1a2e45;">
                                        {{ $record->job_title }}
                                    </div>
                                    @if($record->employer)
                                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $record->employer->company_name }}
                                    </div>
                                    @endif
                                </div>
                                <span class="emp-status-pill esp-{{ $record->employment_status }}">
                                    {{ ucfirst($record->employment_status) }}
                                </span>
                            </div>
                            <div class="record-card-body">
                                <div class="record-meta-row">
                                    <div class="record-meta-item">
                                        <label>Department</label>
                                        <p>{{ $record->department ?? '—' }}</p>
                                    </div>
                                    <div class="record-meta-item">
                                        <label>Start Date</label>
                                        <p>{{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="record-meta-item">
                                        <label>End Date</label>
                                        <p>
                                            {{ $record->end_date
                                                ? \Carbon\Carbon::parse($record->end_date)->format('d M Y')
                                                : 'Present' }}
                                        </p>
                                    </div>
                                    <div class="record-meta-item">
                                        <label>Duration</label>
                                        <p>
                                            @php
                                            $start = \Carbon\Carbon::parse($record->start_date);
                                            $end = $record->end_date
                                            ? \Carbon\Carbon::parse($record->end_date)
                                            : now();
                                            $diff = $start->diff($end);
                                            @endphp
                                            @if($diff->y > 0){{ $diff->y }}y @endif
                                            {{ $diff->m }}m
                                        </p>
                                    </div>
                                    @if($record->exit_reason)
                                    <div class="record-meta-item" style="grid-column:span 2">
                                        <label>Exit Reason</label>
                                        <p>{{ $record->exit_reason }}</p>
                                    </div>
                                    @endif
                                    @if($record->remarks)
                                    <div class="record-meta-item" style="grid-column:span 2">
                                        <label>Remarks</label>
                                        <p style="color:var(--text-muted);">{{ $record->remarks }}</p>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Transfer Requests -->
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6><i class="bi bi-arrow-left-right me-2"></i>Transfer Requests</h6>
                <span style="font-size:12px;color:var(--text-muted);">
                    {{ $employee->transferRequests->count() }} total
                </span>
            </div>

            @if($employee->transferRequests->isEmpty())
            <div class="empty-sub">
                <i class="bi bi-arrow-left-right"></i>
                <p style="font-size:14px;color:var(--text-muted);margin:0;">No transfer requests.</p>
            </div>
            @else
            <div style="overflow-x:auto;">
                <table class="table sub-table mb-0">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Proposed Title</th>
                            <th>Proposed Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->transferRequests->sortByDesc('created_at') as $tr)
                        <tr>
                            <td style="white-space:nowrap;">
                                {{ $tr->currentEmployer->company_name ?? '—' }}
                            </td>
                            <td style="white-space:nowrap;">
                                {{ $tr->requestingEmployer->company_name ?? '—' }}
                            </td>
                            <td>{{ $tr->proposed_job_title ?? '—' }}</td>
                            <td style="white-space:nowrap;">
                                {{ $tr->proposed_start_date ? $tr->proposed_start_date->format('d M Y') : '—' }}
                            </td>
                            <td>
                                <span class="tr-pill tr-{{ $tr->status }}">
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

<<<<<<< HEAD

=======
    </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header" style="border-bottom:1px solid var(--border); padding:20px 24px;">
                <h6 class="modal-title" style="font-family:'Sora',sans-serif; font-weight:700; color:#1a2e45;">
                    Edit Employee
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding:24px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">National ID (NID) *</label>
                            <input type="text" name="nid" class="form-control" value="{{ old('nid', $employee->nid) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male" @selected($employee->gender==='Male' )>Male</option>
                                <option value="Female" @selected($employee->gender==='Female' )>Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" name="dob" class="form-control"
                                value="{{ old('dob', \Carbon\Carbon::parse($employee->dob)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @if($employee->photo)
                            <div class="form-text">Current: {{ basename($employee->photo) }} (upload to replace)</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">District *</label>
                            <input name="district" class="form-control" value="{{ $employee->district }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sector *</label>
                            <input name="sector" class="form-control" required value="{{ $employee->sector }}" >
                           
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="border:1px solid var(--border);">Cancel</button>
                    <button type="submit" class="btn-view"><i class="bi bi-check-lg"></i> Save Changes</button>
                </div>
            </form>
        </div>
>>>>>>> 590859cfd1be133ea9187b0b24fca8f94381ad8f
    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden;">
            <div class="modal-header" style="background:#fff; border-bottom:1px solid var(--border); padding:20px 24px;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba(239,68,68,.1);
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-trash3-fill" style="color:#dc2626;font-size:17px;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title" id="deleteEmployeeModalLabel"
                            style="font-family:'Sora',sans-serif;font-weight:700;font-size:15px;color:#1a2e45;margin:0;">
                            Delete Employee
                        </h6>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">This action cannot be undone.</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div style="background:rgba(239,68,68,.05);border:1px solid rgba(239,68,68,.15);
                            border-radius:12px;padding:16px;">
                    <p style="font-size:14px;color:#7f1d1d;margin:0;">
                        You are about to permanently delete
                        <strong>{{ $employee->full_name }}</strong>
                        (NID: <span class="nid-code" style="font-size:12px;">{{ $employee->nid }}</span>).
                        All associated employment records, transfer requests, and disputes will also be removed.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="padding:16px 24px;border-top:1px solid var(--border);background:#fafbfc;">
                <button type="button" class="btn btn-sm"
                    data-bs-dismiss="modal"
                    style="border:1px solid var(--border);border-radius:9px;
                               padding:8px 20px;font-size:13px;font-weight:600;color:var(--text-muted);">
                    Cancel
                </button>
                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="background:#dc2626;border:none;border-radius:9px;
                                   padding:8px 20px;font-size:13px;font-weight:600;
                                   color:#fff;cursor:pointer;">
                        <i class="bi bi-trash3 me-1"></i>Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection