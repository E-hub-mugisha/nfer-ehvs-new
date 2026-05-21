@extends('layouts.app')

@section('title', 'Dispute #' . $dispute->id)

@section('content')

<div class="dispute-detail-wrapper">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb breadcrumb-gov">
            <li class="breadcrumb-item">
                <a href="{{ route('government.disputes.index') }}">
                    <i class="bi bi-stack me-1"></i>Disputes
                </a>
            </li>
            <li class="breadcrumb-item active">Dispute #{{ $dispute->id }}</li>
        </ol>
    </nav>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="detail-header mb-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <div class="dispute-id-label">Dispute ID</div>
                <h1 class="dispute-title">#{{ $dispute->id }}</h1>
                <div class="d-flex align-items-center gap-2 mt-1">
                    @include('government.disputes._status_badge', ['status' => $dispute->status])
                    <span class="text-muted small">
                        Submitted {{ $dispute->created_at->format('d M Y \a\t H:i') }}
                    </span>
                </div>
            </div>

            {{-- Status Update --}}
            <div class="status-update-panel">
                <div class="panel-label">Update Status</div>
                <form method="POST"
                      action="{{ route('government.disputes.updateStatus', $dispute) }}"
                      class="d-flex gap-2 align-items-center">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm" style="min-width:160px;">
                        <option value="pending"      {{ $dispute->status === 'pending'      ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ $dispute->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="resolved"     {{ $dispute->status === 'resolved'     ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected"     {{ $dispute->status === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-update-status">
                        <i class="bi bi-arrow-repeat me-1"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: Description + Evidence --}}
        <div class="col-lg-8">

            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-chat-left-text me-2"></i>Dispute Description
                </div>
                <div class="detail-card-body">
                    <div class="dispute-description">{{ $dispute->description }}</div>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-paperclip me-2"></i>Submitted Evidence
                </div>
                <div class="detail-card-body">
                    @if($dispute->evidence)
                        @php
                            $isUrl  = filter_var($dispute->evidence, FILTER_VALIDATE_URL);
                            $isFile = Str::startsWith($dispute->evidence, ['uploads/', 'evidence/']);
                        @endphp

                        @if($isUrl)
                            <a href="{{ $dispute->evidence }}" target="_blank" class="evidence-link">
                                <i class="bi bi-box-arrow-up-right me-2"></i>View Submitted Evidence
                            </a>
                        @elseif($isFile)
                            <div class="evidence-file">
                                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                                <div>
                                    <div class="evidence-filename">{{ basename($dispute->evidence) }}</div>
                                    <a href="{{ asset($dispute->evidence) }}" target="_blank" class="evidence-download">
                                        <i class="bi bi-download me-1"></i>Download File
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="evidence-text">{{ $dispute->evidence }}</div>
                        @endif
                    @else
                        <div class="no-evidence">
                            <i class="bi bi-exclamation-circle me-2 text-warning"></i>
                            No evidence was submitted with this dispute.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right: Employee + Employment Record + Timeline --}}
        <div class="col-lg-4">

            {{-- Employee --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-person me-2"></i>Employee
                </div>
                <div class="detail-card-body">
                    @if($dispute->employee)
                        <div class="profile-block mb-3">
                            <div class="profile-avatar">
                                {{ strtoupper(substr($dispute->employee->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="profile-name">{{ $dispute->employee->full_name }}</div>
                                <div class="profile-sub">{{ $dispute->employee->national_id ?? '—' }}</div>
                            </div>
                        </div>
                        <table class="info-table">
                            @if(!empty($dispute->employee->email))
                                <tr>
                                    <th><i class="bi bi-envelope me-1"></i>Email</th>
                                    <td>{{ $dispute->employee->email }}</td>
                                </tr>
                            @endif
                            @if(!empty($dispute->employee->phone))
                                <tr>
                                    <th><i class="bi bi-telephone me-1"></i>Phone</th>
                                    <td>{{ $dispute->employee->phone }}</td>
                                </tr>
                            @endif
                            @if(!empty($dispute->employee->date_of_birth))
                                <tr>
                                    <th><i class="bi bi-calendar me-1"></i>DOB</th>
                                    <td>{{ \Carbon\Carbon::parse($dispute->employee->date_of_birth)->format('d M Y') }}</td>
                                </tr>
                            @endif
                        </table>
                    @else
                        <p class="text-muted mb-0 small">Employee record not available.</p>
                    @endif
                </div>
            </div>

            {{-- Employment Record --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-briefcase me-2"></i>Employment Record
                </div>
                <div class="detail-card-body">
                    @if($dispute->employmentRecord)
                        @php $rec = $dispute->employmentRecord; @endphp
                        <table class="info-table">
                            <tr>
                                <th>Record #</th>
                                <td>{{ $rec->id }}</td>
                            </tr>
                            @if(!empty($rec->employer))
                                <tr>
                                    <th>Employer</th>
                                    <td>{{ $rec->employer->name ?? '—' }}</td>
                                </tr>
                            @endif
                            @if(!empty($rec->job_title))
                                <tr>
                                    <th>Job Title</th>
                                    <td>{{ $rec->job_title }}</td>
                                </tr>
                            @endif
                            @if(!empty($rec->start_date))
                                <tr>
                                    <th>Start Date</th>
                                    <td>{{ \Carbon\Carbon::parse($rec->start_date)->format('d M Y') }}</td>
                                </tr>
                            @endif
                            @if(!empty($rec->end_date))
                                <tr>
                                    <th>End Date</th>
                                    <td>{{ \Carbon\Carbon::parse($rec->end_date)->format('d M Y') }}</td>
                                </tr>
                            @endif
                            @if(!empty($rec->salary))
                                <tr>
                                    <th>Salary</th>
                                    <td>RWF {{ number_format($rec->salary) }}</td>
                                </tr>
                            @endif
                            @if(!empty($rec->status))
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="record-status-badge record-status--{{ $rec->status }}">
                                            {{ ucfirst($rec->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    @else
                        <p class="text-muted mb-0 small">Employment record not available.</p>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-clock-history me-2"></i>Timeline
                </div>
                <div class="detail-card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot timeline-dot--create"></div>
                            <div>
                                <div class="timeline-label">Dispute Submitted</div>
                                <div class="timeline-date">{{ $dispute->created_at->format('d M Y, H:i') }}</div>
                                <div class="timeline-relative">{{ $dispute->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @if($dispute->updated_at->ne($dispute->created_at))
                            <div class="timeline-item">
                                <div class="timeline-dot timeline-dot--update"></div>
                                <div>
                                    <div class="timeline-label">Last Updated</div>
                                    <div class="timeline-date">{{ $dispute->updated_at->format('d M Y, H:i') }}</div>
                                    <div class="timeline-relative">{{ $dispute->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Back --}}
    <div class="mt-4 pt-2 border-top">
        <a href="{{ route('government.disputes.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i>Back to All Disputes
        </a>
    </div>

</div>


<style>
    /* .dispute-detail-wrapper { max-width:1100px; } */

    /* Breadcrumb */
    .breadcrumb-gov         { font-size:.82rem; margin:0; }
    .breadcrumb-gov a       { color:#4b6cb7; text-decoration:none; }
    .breadcrumb-gov a:hover { text-decoration:underline; }
    .breadcrumb-gov .breadcrumb-item.active { color:#6c757d; }

    /* Page Header */
    .dispute-id-label { font-size:.72rem; font-weight:700; text-transform:uppercase;
                        letter-spacing:.8px; color:#9aa0b4; }
    .dispute-title    { font-size:2rem; font-weight:700; color:#1a2a5e; margin:0; line-height:1.15; }

    /* Status Update Panel */
    .status-update-panel { background:#f8f9fc; border:1.5px solid #e8eaf0;
                           border-radius:12px; padding:.85rem 1rem; }
    .panel-label         { font-size:.72rem; font-weight:700; text-transform:uppercase;
                           letter-spacing:.6px; color:#9aa0b4; margin-bottom:.5rem; }
    .btn-update-status   { background:#1a2a5e; color:#fff; font-size:.82rem; font-weight:600;
                           border-radius:8px; border:none; padding:.38rem .9rem; }
    .btn-update-status:hover { background:#152248; color:#fff; }

    /* Status Badges */
    .status-badge           { display:inline-flex; align-items:center; gap:.3rem; font-size:.78rem;
                              font-weight:600; padding:.32rem .75rem; border-radius:50px;
                              text-transform:capitalize; letter-spacing:.2px; }
    .status-badge--pending      { background:#fef3c7; color:#92400e; }
    .status-badge--under_review { background:#dbeafe; color:#1d4ed8; }
    .status-badge--resolved     { background:#d1fae5; color:#065f46; }
    .status-badge--rejected     { background:#fee2e2; color:#991b1b; }

    /* Detail Cards */
    .detail-card         { background:#fff; border:1.5px solid #e8eaf0;
                           border-radius:14px; overflow:hidden; }
    .detail-card-header  { background:#f8f9fc; border-bottom:1.5px solid #e8eaf0;
                           padding:.8rem 1.2rem; font-size:.75rem; font-weight:700;
                           text-transform:uppercase; letter-spacing:.6px; color:#4b5573; }
    .detail-card-body    { padding:1.25rem; }
    .dispute-description { font-size:.95rem; color:#3a4060; line-height:1.75; white-space:pre-line; }

    /* Evidence */
    .evidence-link  { display:inline-flex; align-items:center; color:#1a2a5e; font-weight:600;
                      font-size:.9rem; background:#eef0fb; padding:.55rem 1rem; border-radius:8px;
                      text-decoration:none; border:1px solid #d0d5ef; transition:all .2s; }
    .evidence-link:hover { background:#1a2a5e; color:#fff; }
    .evidence-file  { display:flex; align-items:center; gap:.75rem; background:#f8f9fc;
                      border:1px dashed #c8cedf; border-radius:10px; padding:.85rem 1rem; }
    .evidence-filename { font-weight:600; font-size:.88rem; color:#2c3a6b; margin-bottom:.15rem; }
    .evidence-download { font-size:.78rem; color:#4b6cb7; text-decoration:none; font-weight:500; }
    .evidence-download:hover { text-decoration:underline; }
    .evidence-text  { font-size:.88rem; color:#495057; background:#f8f9fc; border:1px solid #e8eaf0;
                      border-radius:8px; padding:.85rem 1rem; white-space:pre-line; }
    .no-evidence    { font-size:.88rem; color:#6c757d; }

    /* Profile */
    .profile-block  { display:flex; align-items:center; gap:.85rem; }
    .profile-avatar { width:44px; height:44px; border-radius:50%; flex-shrink:0;
                      background:linear-gradient(135deg,#1a2a5e,#2d4a9e);
                      color:#fff; font-size:.85rem; font-weight:700;
                      display:flex; align-items:center; justify-content:center; }
    .profile-name   { font-weight:700; color:#1a2a5e; font-size:.95rem; }
    .profile-sub    { font-size:.78rem; color:#9aa0b4; }

    /* Info Table */
    .info-table         { width:100%; border-collapse:separate; border-spacing:0 .1rem; font-size:.85rem; }
    .info-table th      { color:#9aa0b4; font-weight:600; width:40%; padding:.3rem 0;
                          vertical-align:top; white-space:nowrap; }
    .info-table td      { color:#2c3a6b; font-weight:500; padding:.3rem 0; word-break:break-word; }

    /* Record Status */
    .record-status-badge       { font-size:.72rem; font-weight:600; padding:.2rem .55rem; border-radius:50px; }
    .record-status--active     { background:#d1fae5; color:#065f46; }
    .record-status--inactive   { background:#f3f4f6; color:#6b7280; }
    .record-status--terminated { background:#fee2e2; color:#991b1b; }

    /* Timeline */
    .timeline       { display:flex; flex-direction:column; gap:1rem; }
    .timeline-item  { display:flex; align-items:flex-start; gap:.75rem; }
    .timeline-dot   { width:10px; height:10px; border-radius:50%; margin-top:.25rem; flex-shrink:0; }
    .timeline-dot--create { background:#1a2a5e; }
    .timeline-dot--update { background:#3b82f6; }
    .timeline-label    { font-size:.75rem; font-weight:700; color:#4b5573;
                         text-transform:uppercase; letter-spacing:.4px; }
    .timeline-date     { font-size:.85rem; color:#2c3a6b; font-weight:500; margin-top:.1rem; }
    .timeline-relative { font-size:.75rem; color:#9aa0b4; }

    /* Back Button */
    .btn-back { background:#f8f9fc; color:#1a2a5e; border:1.5px solid #e8eaf0;
                font-size:.875rem; font-weight:600; border-radius:10px;
                padding:.5rem 1.1rem; text-decoration:none; transition:all .2s; }
    .btn-back:hover { background:#1a2a5e; color:#fff; border-color:#1a2a5e; }
</style>
@endsection