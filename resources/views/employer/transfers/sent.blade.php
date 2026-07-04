@extends('layouts.app')
@section('title', 'Sent Transfer Requests')

@section('content')
<style>
    :root {
        --tr-primary: #4f46e5;
        --tr-primary-light: #eef2ff;
        --tr-primary-dark: #3730a3;
        --tr-success: #16a34a;
        --tr-success-light: #ecfdf3;
        --tr-danger: #dc2626;
        --tr-danger-light: #fef2f2;
        --tr-warning: #d97706;
        --tr-warning-light: #fffbeb;
        --tr-border: #eef0f4;
        --tr-muted: #8a8fa3;
        --tr-text: #1e2130;
    }

    .tr-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .tr-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .tr-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--tr-primary), #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.15rem;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.28);
        flex-shrink: 0;
    }

    .tr-title-wrap h5 {
        font-weight: 700;
        color: var(--tr-text);
        margin-bottom: 0.1rem;
    }

    .tr-title-wrap .tr-subtitle {
        color: var(--tr-muted);
        font-size: 0.82rem;
    }

    .tr-count-chip {
        background: var(--tr-primary-light);
        color: var(--tr-primary);
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .tr-alert {
        border: none;
        border-radius: 14px;
        background: var(--tr-success-light);
        color: #166534;
        padding: 0.9rem 1.15rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .tr-card {
        border: none;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(20, 20, 43, 0.05);
        overflow: hidden;
    }

    .tr-empty {
        padding: 4rem 1rem;
        text-align: center;
    }

    .tr-empty i {
        font-size: 2.75rem;
        color: #d7dae3;
        display: block;
        margin-bottom: 0.75rem;
    }

    .tr-empty p {
        color: var(--tr-muted);
        font-weight: 500;
        margin: 0;
    }

    .tr-table {
        margin-bottom: 0;
        width: 100%;
    }

    .tr-table thead th {
        background: #fafbfc;
        color: var(--tr-muted);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--tr-border);
        padding: 0.95rem 1.1rem;
        white-space: nowrap;
    }

    .tr-table tbody td {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid var(--tr-border);
        vertical-align: middle;
    }

    .tr-table tbody tr:last-child td {
        border-bottom: none;
    }

    .tr-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .tr-table tbody tr:hover {
        background-color: #fafbfe;
    }

    .tr-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--tr-primary-light);
        color: var(--tr-primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .tr-employee-cell {
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .tr-employee-name {
        font-weight: 600;
        color: var(--tr-text);
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .tr-employee-nid {
        color: var(--tr-muted);
        font-size: 0.76rem;
    }

    .tr-role-title {
        font-weight: 600;
        color: var(--tr-text);
        font-size: 0.88rem;
    }

    .tr-role-dept {
        color: var(--tr-muted);
        font-size: 0.76rem;
    }

    .tr-company {
        font-size: 0.87rem;
        color: var(--tr-text);
        font-weight: 500;
    }

    .tr-date {
        font-size: 0.85rem;
        color: var(--tr-text);
        font-weight: 500;
        white-space: nowrap;
    }

    .tr-reason {
        max-width: 220px;
        font-size: 0.82rem;
        color: var(--tr-muted);
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .tr-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .tr-status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .tr-status-pending {
        background: var(--tr-warning-light);
        color: var(--tr-warning);
    }
    .tr-status-pending::before { background: var(--tr-warning); }

    .tr-status-approved {
        background: var(--tr-success-light);
        color: var(--tr-success);
    }
    .tr-status-approved::before { background: var(--tr-success); }

    .tr-status-rejected {
        background: var(--tr-danger-light);
        color: var(--tr-danger);
    }
    .tr-status-rejected::before { background: var(--tr-danger); }

    .tr-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .tr-btn {
        border: none;
        border-radius: 9px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.45rem 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .tr-btn-approve {
        background: var(--tr-success-light);
        color: var(--tr-success);
    }
    .tr-btn-approve:hover { background: var(--tr-success); color: #fff; }

    .tr-btn-reject {
        background: var(--tr-danger-light);
        color: var(--tr-danger);
    }
    .tr-btn-reject:hover { background: var(--tr-danger); color: #fff; }

    .tr-btn-view {
        background: var(--tr-primary-light);
        color: var(--tr-primary);
    }
    .tr-btn-view:hover { background: var(--tr-primary); color: #fff; }

    .tr-responded {
        font-size: 0.78rem;
        color: var(--tr-muted);
        font-style: italic;
        white-space: nowrap;
    }

    /* Modal styling */
    .tr-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
    }

    .tr-modal .modal-header {
        border-bottom: 1px solid var(--tr-border);
        padding: 1.25rem 1.5rem;
    }

    .tr-modal .modal-header h6 {
        font-weight: 700;
        color: var(--tr-text);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
    }

    .tr-modal-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .tr-modal-icon.approve {
        background: var(--tr-success-light);
        color: var(--tr-success);
    }

    .tr-modal-icon.reject {
        background: var(--tr-danger-light);
        color: var(--tr-danger);
    }

    .tr-modal .modal-body {
        padding: 1.5rem;
    }

    .tr-modal label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--tr-text);
        margin-bottom: 0.5rem;
    }

    .tr-modal textarea.form-control {
        border-radius: 12px;
        border: 1px solid var(--tr-border);
        font-size: 0.88rem;
        padding: 0.75rem;
        resize: none;
    }

    .tr-modal textarea.form-control:focus {
        border-color: var(--tr-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .tr-modal .modal-footer {
        border-top: 1px solid var(--tr-border);
        padding: 1rem 1.5rem;
    }

    .tr-modal .btn-cancel {
        background: #f4f5f8;
        color: var(--tr-muted);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.55rem 1.1rem;
    }

    .tr-modal .btn-confirm-approve {
        background: var(--tr-success);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.55rem 1.2rem;
    }

    .tr-modal .btn-confirm-reject {
        background: var(--tr-danger);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.55rem 1.2rem;
    }

    .tr-pagination-wrap {
        margin-top: 1.25rem;
    }

    /* Details modal */
    .tr-detail-modal .modal-dialog {
        max-width: 560px;
    }

    .tr-detail-header {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .tr-detail-avatar {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--tr-primary-light);
        color: var(--tr-primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .tr-detail-name {
        font-weight: 700;
        font-size: 0.98rem;
        color: var(--tr-text);
        line-height: 1.3;
    }

    .tr-detail-nid {
        font-size: 0.78rem;
        color: var(--tr-muted);
    }

    .tr-detail-section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--tr-muted);
        margin: 1.25rem 0 0.65rem;
    }

    .tr-detail-section-label:first-of-type {
        margin-top: 0;
    }

    .tr-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.9rem 1rem;
        background: #fafbfc;
        border: 1px solid var(--tr-border);
        border-radius: 14px;
        padding: 1rem 1.1rem;
    }

    .tr-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .tr-detail-item.full {
        grid-column: 1 / -1;
    }

    .tr-detail-item-label {
        font-size: 0.7rem;
        color: var(--tr-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .tr-detail-item-value {
        font-size: 0.88rem;
        color: var(--tr-text);
        font-weight: 500;
    }

    .tr-detail-reason-box {
        background: #fafbfc;
        border: 1px solid var(--tr-border);
        border-radius: 14px;
        padding: 0.9rem 1.1rem;
        font-size: 0.87rem;
        color: var(--tr-text);
        line-height: 1.5;
    }

    .tr-detail-footer-note {
        font-size: 0.78rem;
        color: var(--tr-muted);
        margin-top: 0.9rem;
    }

    @media (max-width: 768px) {
        .tr-reason { display: none; }
        .tr-table thead th:nth-child(5) { display: none; }
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="tr-page-header">
        <div class="tr-title-wrap">
            <div class="tr-icon-badge">
                <i class="bi bi-send"></i>
            </div>
            <div>
                <h5 class="mb-0">Sent Transfer Requests</h5>
                <div class="tr-subtitle">Transfer requests you've sent to other employers</div>
            </div>
        </div>
        @if(!$requests->isEmpty())
            <span class="tr-count-chip">{{ $requests->total() ?? $requests->count() }} total</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert tr-alert d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="tr-card">
            <div class="tr-empty">
                <i class="bi bi-send"></i>
                <p>You haven't sent any transfer requests yet.</p>
            </div>
        </div>
    @else
        <div class="tr-card">
            <div class="table-responsive">
                <table class="table tr-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Current Employer</th>
                            <th>Proposed Role</th>
                            <th>Start Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td>
                                <div class="tr-employee-cell">
                                    <div class="tr-avatar">
                                        {{ strtoupper(substr($req->employee->first_name, 0, 1) . substr($req->employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="tr-employee-name">
                                            {{ $req->employee->first_name }} {{ $req->employee->last_name }}
                                        </div>
                                        @if($req->employee->nid)
                                            <div class="tr-employee-nid">{{ $req->employee->nid }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tr-company">{{ $req->currentEmployer->company_name }}</span>
                            </td>
                            <td>
                                <div class="tr-role-title">{{ $req->proposed_job_title }}</div>
                                @if($req->proposed_department)
                                    <div class="tr-role-dept">{{ $req->proposed_department }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="tr-date">{{ $req->proposed_start_date->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="tr-reason" title="{{ $req->reason }}">
                                    {{ $req->reason ?? '—' }}
                                </div>
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="tr-status-pill tr-status-pending">Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="tr-status-pill tr-status-approved">Approved</span>
                                @else
                                    <span class="tr-status-pill tr-status-rejected">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="tr-actions">
                                    <span class="tr-responded">
                                        {{ $req->created_at->diffForHumans() }}
                                    </span>
                                    <button class="tr-btn tr-btn-view"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewModal{{ $req->id }}">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </div>

                                {{-- View Details Modal --}}
                                <div class="modal fade tr-modal tr-detail-modal" id="viewModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="tr-detail-header">
                                                    <div class="tr-detail-avatar">
                                                        {{ strtoupper(substr($req->employee->first_name, 0, 1) . substr($req->employee->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="tr-detail-name">
                                                            {{ $req->employee->first_name }} {{ $req->employee->last_name }}
                                                        </div>
                                                        @if($req->employee->nid)
                                                            <div class="tr-detail-nid">{{ $req->employee->nid }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="tr-detail-section-label mb-0">Transfer Request</span>
                                                    @if($req->status === 'pending')
                                                        <span class="tr-status-pill tr-status-pending">Pending</span>
                                                    @elseif($req->status === 'approved')
                                                        <span class="tr-status-pill tr-status-approved">Approved</span>
                                                    @else
                                                        <span class="tr-status-pill tr-status-rejected">Rejected</span>
                                                    @endif
                                                </div>

                                                <div class="tr-detail-grid">
                                                    <div class="tr-detail-item">
                                                        <span class="tr-detail-item-label">Current Employer</span>
                                                        <span class="tr-detail-item-value">{{ $req->currentEmployer->company_name }}</span>
                                                    </div>
                                                    <div class="tr-detail-item">
                                                        <span class="tr-detail-item-label">Proposed Start Date</span>
                                                        <span class="tr-detail-item-value">{{ $req->proposed_start_date->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="tr-detail-item">
                                                        <span class="tr-detail-item-label">Proposed Role</span>
                                                        <span class="tr-detail-item-value">{{ $req->proposed_job_title }}</span>
                                                    </div>
                                                    <div class="tr-detail-item">
                                                        <span class="tr-detail-item-label">Department</span>
                                                        <span class="tr-detail-item-value">{{ $req->proposed_department ?? '—' }}</span>
                                                    </div>
                                                    @if($req->employee->nid)
                                                        <div class="tr-detail-item">
                                                            <span class="tr-detail-item-label">National ID</span>
                                                            <span class="tr-detail-item-value">{{ $req->employee->nid }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="tr-detail-item">
                                                        <span class="tr-detail-item-label">Sent</span>
                                                        <span class="tr-detail-item-value">{{ $req->created_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                </div>

                                                <div class="tr-detail-section-label">Reason</div>
                                                <div class="tr-detail-reason-box">
                                                    {{ $req->reason ?? 'No reason provided.' }}
                                                </div>

                                                @if(!$req->isPending())
                                                    <div class="tr-detail-section-label">Response</div>
                                                    <div class="tr-detail-grid">
                                                        <div class="tr-detail-item">
                                                            <span class="tr-detail-item-label">Responded</span>
                                                            <span class="tr-detail-item-value">
                                                                {{ $req->responded_at?->format('d M Y, H:i') ?? '—' }}
                                                            </span>
                                                        </div>
                                                        <div class="tr-detail-item">
                                                            <span class="tr-detail-item-label">Outcome</span>
                                                            <span class="tr-detail-item-value">{{ ucfirst($req->status) }}</span>
                                                        </div>
                                                        @if($req->status === 'rejected' && $req->rejection_reason)
                                                            <div class="tr-detail-item full">
                                                                <span class="tr-detail-item-label">Rejection Note</span>
                                                                <span class="tr-detail-item-value">{{ $req->rejection_reason }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="tr-detail-footer-note">
                                                        <i class="bi bi-hourglass-split me-1"></i>
                                                        Awaiting response from {{ $req->currentEmployer->company_name }}.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-cancel"
                                                        data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tr-pagination-wrap">{{ $requests->links() }}</div>
    @endif
</div>
@endsection