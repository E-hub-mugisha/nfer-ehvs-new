@extends('layouts.app')

@section('title', 'Transfer Request #' . $transferRequest->id)

@section('content')
<div class="tr-show-page">

    {{-- Back --}}
    <a href="{{ route('admin.transfer-requests.index') }}" class="back-link">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Transfer Requests
    </a>

    {{-- Header --}}
    <div class="show-header">
        <div>
            <h1 class="show-title">Transfer Request <span>#{{ $transferRequest->id }}</span></h1>
            <p class="show-meta">Submitted {{ $transferRequest->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="status-badge {{ $transferRequest->status }}">{{ ucfirst($transferRequest->status) }}</span>
    </div>

    <div class="show-grid">

        {{-- Employee --}}
        <div class="show-card">
            <div class="card-label">Employee</div>
            <div class="card-name">{{ $transferRequest->employee->full_name ?? '—' }}</div>
            @if($transferRequest->employee)
            <div class="card-sub">{{ $transferRequest->employee->email ?? '' }}</div>
            <a href="{{ route('admin.employees.show', $transferRequest->employee) }}" class="card-link">View profile →</a>
            @endif
        </div>

        {{-- Current Employer --}}
        <div class="show-card">
            <div class="card-label">Current Employer</div>
            <div class="card-name">{{ $transferRequest->currentEmployer->name ?? '—' }}</div>
            @if($transferRequest->currentEmploymentRecord)
            <div class="card-sub">{{ $transferRequest->currentEmploymentRecord->job_title ?? '' }}</div>
            @if($transferRequest->currentEmploymentRecord->department)
            <div class="card-sub">{{ $transferRequest->currentEmploymentRecord->department }}</div>
            @endif
            @endif
        </div>

        {{-- Requesting Employer --}}
        <div class="show-card accent">
            <div class="card-label">Requesting Employer</div>
            <div class="card-name">{{ $transferRequest->requestingEmployer->name ?? '—' }}</div>
            <div class="card-sub">Proposed role:</div>
            <div class="card-proposed-title">{{ $transferRequest->proposed_job_title }}</div>
            @if($transferRequest->proposed_department)
            <div class="card-sub">{{ $transferRequest->proposed_department }}</div>
            @endif
            @if($transferRequest->proposed_start_date)
            <div class="card-date-chip">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2" />
                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2" />
                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2" />
                    <line x1="3" y1="10" x2="21" y2="10" stroke-width="2" />
                </svg>
                Start {{ $transferRequest->proposed_start_date->format('d M Y') }}
            </div>
            @endif
        </div>

    </div>

    {{-- Reason --}}
    <div class="show-section">
        <div class="section-label">Reason for Transfer</div>
        <div class="section-body">{{ $transferRequest->reason ?: 'No reason provided.' }}</div>
    </div>

    {{-- Rejection reason --}}
    @if($transferRequest->status === 'rejected' && $transferRequest->rejection_reason)
    <div class="show-section rejection">
        <div class="section-label">Rejection Reason</div>
        <div class="section-body">{{ $transferRequest->rejection_reason }}</div>
        @if($transferRequest->responded_at)
        <div class="section-meta">Responded {{ $transferRequest->responded_at->format('d M Y, H:i') }}</div>
        @endif
    </div>
    @endif

    @if($transferRequest->status === 'approved' && $transferRequest->responded_at)
    <div class="show-section approved-note">
        <div class="section-label">Approved</div>
        <div class="section-meta">Approved on {{ $transferRequest->responded_at->format('d M Y, H:i') }}</div>
    </div>
    @endif

    {{-- Actions for pending --}}
    @if($transferRequest->isPending())
    <div class="show-actions">
        <form method="POST" action="{{ route('admin.transfer-requests.approve', $transferRequest) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn-approve">Approve Transfer</button>
        </form>

        <button type="button" class="btn-reject" onclick="document.getElementById('reject-panel').classList.toggle('open')">
            Reject
        </button>
    </div>

    <div id="reject-panel" class="reject-panel">
        <form method="POST" action="{{ route('admin.transfer-requests.reject', $transferRequest) }}">
            @csrf @method('PATCH')
            <label for="rejection_reason">Rejection Reason</label>
            <textarea id="rejection_reason" name="rejection_reason" rows="3" placeholder="Briefly explain the decision…" required></textarea>
            <div class="reject-actions">
                <button type="submit" class="btn-confirm-reject">Confirm Rejection</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('reject-panel').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
    @endif

</div>

<style>
    .tr-show-page {
        padding: 2rem;
        font-family: 'DM Sans', sans-serif;
        color: #e2e8ea;
        max-width: 900px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: #7a9198;
        font-size: .85rem;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }

    .back-link:hover {
        color: #00a667;
    }

    /* Header */
    .show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .show-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 .3rem;
    }

    .show-title span {
        color: #00a667;
    }

    .show-meta {
        font-size: .82rem;
        color: #7a9198;
        margin: 0;
    }

    /* Status badges */
    .status-badge {
        padding: .3rem .9rem;
        border-radius: 99px;
        font-size: .78rem;
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

    /* Cards grid */
    .show-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .show-card {
        background: #111b1e;
        border: 1px solid #1a2a2e;
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
    }

    .show-card.accent {
        border-color: rgba(0, 166, 103, .3);
        background: rgba(0, 166, 103, .04);
    }

    .card-label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #7a9198;
        margin-bottom: .55rem;
    }

    .card-name {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: .25rem;
    }

    .card-proposed-title {
        font-weight: 600;
        color: #00a667;
        font-size: .95rem;
        margin: .15rem 0 .25rem;
    }

    .card-sub {
        font-size: .8rem;
        color: #7a9198;
        line-height: 1.5;
    }

    .card-link {
        display: inline-block;
        margin-top: .6rem;
        font-size: .78rem;
        color: #00a667;
        text-decoration: none;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    .card-date-chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin-top: .65rem;
        background: rgba(0, 166, 103, .1);
        color: #00a667;
        font-size: .76rem;
        font-weight: 600;
        padding: .25rem .65rem;
        border-radius: 6px;
    }

    /* Sections */
    .show-section {
        background: #111b1e;
        border: 1px solid #1a2a2e;
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
        margin-bottom: 1rem;
    }

    .show-section.rejection {
        border-color: rgba(239, 68, 68, .3);
    }

    .show-section.approved-note {
        border-color: rgba(0, 166, 103, .3);
    }

    .section-label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #7a9198;
        margin-bottom: .6rem;
    }

    .section-body {
        font-size: .9rem;
        line-height: 1.7;
        color: #c8d6da;
    }

    .section-meta {
        font-size: .78rem;
        color: #7a9198;
        margin-top: .6rem;
    }

    /* Actions */
    .show-actions {
        display: flex;
        gap: .75rem;
        margin-top: 1.75rem;
        flex-wrap: wrap;
    }

    .btn-approve {
        background: #00a667;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .6rem 1.4rem;
        font-size: .9rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-approve:hover {
        background: #009059;
    }

    .btn-reject {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, .4);
        color: #ef4444;
        border-radius: 8px;
        padding: .6rem 1.2rem;
        font-size: .9rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reject:hover {
        background: rgba(239, 68, 68, .08);
    }

    /* Reject panel */
    .reject-panel {
        display: none;
        background: #111b1e;
        border: 1px solid rgba(239, 68, 68, .3);
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
        margin-top: .75rem;
    }

    .reject-panel.open {
        display: block;
    }

    .reject-panel label {
        display: block;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #7a9198;
        margin-bottom: .5rem;
    }

    .reject-panel textarea {
        width: 100%;
        background: #0e1618;
        border: 1px solid #1a2a2e;
        color: #e2e8ea;
        border-radius: 8px;
        padding: .65rem .85rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .875rem;
        resize: vertical;
        outline: none;
        box-sizing: border-box;
    }

    .reject-panel textarea:focus {
        border-color: #ef4444;
    }

    .reject-actions {
        display: flex;
        gap: .6rem;
        margin-top: .75rem;
    }

    .btn-confirm-reject {
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .5rem 1.1rem;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-confirm-reject:hover {
        background: #dc2626;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid #1a2a2e;
        color: #7a9198;
        border-radius: 8px;
        padding: .5rem 1rem;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
    }

    .btn-cancel:hover {
        border-color: #7a9198;
        color: #e2e8ea;
    }
</style>
@endsection