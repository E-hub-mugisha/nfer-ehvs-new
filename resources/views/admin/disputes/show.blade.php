@extends('layouts.app')

@section('title', 'Dispute #' . $dispute->id)

@section('content')

<style>
    /* ── Page ─────────────────────────────────────────── */
    .dispute-show-page {
        padding: 1.5rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ── Breadcrumb ───────────────────────────────────── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1.25rem;
        font-size: .875rem;
    }

    .breadcrumb__link {
        color: #2563eb;
        text-decoration: none;
    }

    .breadcrumb__link:hover {
        text-decoration: underline;
    }

    .breadcrumb svg {
        color: #d1d5db;
    }

    .breadcrumb__current {
        color: #6b7280;
    }

    /* ── Alert ────────────────────────────────────────── */
    .alert {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .875rem 1rem;
        border-radius: .5rem;
        font-size: .875rem;
        margin-bottom: 1.25rem;
    }

    .alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    /* ── Page Header ──────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        gap: 1rem;
    }

    .page-header__meta {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: .5rem;
    }

    .page-header__date {
        font-size: .8125rem;
        color: #9ca3af;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* ── Content Grid ─────────────────────────────────── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    .left-col,
    .right-col {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ── Info Cards ───────────────────────────────────── */
    .info-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        overflow: hidden;
    }

    .info-card--action {
        border-color: #bfdbfe;
    }

    .info-card__header {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .875rem 1.25rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        font-size: .8125rem;
        font-weight: 600;
        color: #374151;
    }

    .info-card--action .info-card__header {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .info-card__body {
        padding: 1.25rem;
    }

    /* ── Employee Profile ─────────────────────────────── */
    .employee-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .employee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: .75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .employee-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .employee-avatar--lg {
        width: 52px;
        height: 52px;
        font-size: 1.125rem;
    }

    .employee-profile__name {
        font-weight: 600;
        color: #111827;
        font-size: 1rem;
    }

    .employee-profile__nid {
        font-size: .8125rem;
        color: #6b7280;
        margin-top: .125rem;
        font-family: monospace;
    }

    /* ── Detail Rows ──────────────────────────────────── */
    .detail-rows {
        display: flex;
        flex-direction: column;
        gap: .625rem;
    }

    .detail-row {
        display: flex;
        align-items: flex-start;
        gap: .5rem;
        font-size: .875rem;
    }

    .detail-row__label {
        min-width: 110px;
        color: #9ca3af;
        font-size: .8125rem;
        padding-top: .05rem;
        flex-shrink: 0;
    }

    .detail-row__value {
        color: #374151;
        flex: 1;
    }

    .fw-600 {
        font-weight: 600;
    }

    /* ── Badges ───────────────────────────────────────── */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: .25rem .625rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge--amber {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge--green {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge--red {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .badge--blue {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* ── Dispute Description ──────────────────────────── */
    .dispute-description {
        font-size: .9375rem;
        color: #374151;
        line-height: 1.7;
        margin: 0;
        white-space: pre-line;
    }

    /* ── Evidence ─────────────────────────────────────── */
    .evidence-image {
        width: 100%;
        border-radius: .5rem;
        margin-bottom: .875rem;
        border: 1px solid #e5e7eb;
    }

    .evidence-link {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        font-size: .875rem;
        color: #2563eb;
        text-decoration: none;
        padding: .5rem .875rem;
        border-radius: .5rem;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        transition: background .15s;
    }

    .evidence-link:hover {
        background: #dbeafe;
    }

    /* ── Status Form ──────────────────────────────────── */
    .status-form {
        display: flex;
        flex-direction: column;
        gap: .875rem;
    }

    .status-form__field {
        display: flex;
        flex-direction: column;
        gap: .375rem;
    }

    .form-label {
        font-size: .8125rem;
        font-weight: 500;
        color: #374151;
    }

    .form-select {
        height: 40px;
        padding: 0 .875rem;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        font-size: .875rem;
        color: #374151;
        background: #fff;
        outline: none;
        transition: border-color .15s;
    }

    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px #dbeafe;
    }

    .form-select--full {
        width: 100%;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: 0 1.125rem;
        height: 40px;
        border-radius: .5rem;
        font-size: .875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-ghost {
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .btn-ghost:hover {
        background: #f9fafb;
        color: #374151;
    }

    .btn--full {
        width: 100%;
    }

    /* ── Timeline ─────────────────────────────────────── */
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .timeline__item {
        display: flex;
        gap: .875rem;
        position: relative;
        padding-bottom: 1.25rem;
    }

    .timeline__item:last-child {
        padding-bottom: 0;
    }

    .timeline__item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 16px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline__dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
        background: #2563eb;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #bfdbfe;
        margin-top: .125rem;
    }

    .timeline__dot--gray {
        background: #d1d5db;
        box-shadow: 0 0 0 2px #e5e7eb;
    }

    .timeline__title {
        font-size: .875rem;
        font-weight: 600;
        color: #111827;
    }

    .timeline__date {
        font-size: .8125rem;
        color: #9ca3af;
        margin-top: .125rem;
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 900px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .dispute-show-page {
            padding: 1rem;
        }
    }
</style>

<div class="dispute-show-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('admin.disputes.index') }}" class="breadcrumb__link">Disputes</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span class="breadcrumb__current">Dispute #{{ $dispute->id }}</span>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <div class="page-header__meta">
                <span class="badge badge--{{ $dispute->status === 'pending' ? 'amber' : ($dispute->status === 'resolved' ? 'green' : ($dispute->status === 'rejected' ? 'red' : 'blue')) }}">
                    {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                </span>
                <span class="page-header__date">Filed {{ $dispute->created_at->format('d M Y, g:i A') }}</span>
            </div>
            <h1 class="page-title">Dispute #{{ $dispute->id }}</h1>
        </div>
        <a href="{{ route('admin.disputes.index') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Back to Disputes
        </a>
    </div>

    <div class="content-grid">

        {{-- Left Column --}}
        <div class="left-col">

            {{-- Employee Info --}}
            <div class="info-card">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Employee Information
                </div>
                <div class="info-card__body">
                    <div class="employee-profile">
                        <div class="employee-avatar employee-avatar--lg">
                            @if($dispute->employee->photo)
                            <img src="{{ asset('storage/' . $dispute->employee->photo) }}" alt="{{ $dispute->employee->full_name }}">
                            @else
                            {{ strtoupper(substr($dispute->employee->first_name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="employee-profile__name">{{ $dispute->employee->full_name ?? '—' }}</div>
                            <div class="employee-profile__nid">NID: {{ $dispute->employee->nid ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="detail-rows">
                        <div class="detail-row">
                            <span class="detail-row__label">Gender</span>
                            <span class="detail-row__value">{{ ucfirst($dispute->employee->gender ?? '—') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Phone</span>
                            <span class="detail-row__value">{{ $dispute->employee->phone ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Email</span>
                            <span class="detail-row__value">{{ $dispute->employee->email ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">District</span>
                            <span class="detail-row__value">{{ $dispute->employee->district ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Sector</span>
                            <span class="detail-row__value">{{ $dispute->employee->sector ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employment Record Info --}}
            <div class="info-card">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                    </svg>
                    Employment Record
                </div>
                <div class="info-card__body">
                    <div class="detail-rows">
                        <div class="detail-row">
                            <span class="detail-row__label">Employer</span>
                            <span class="detail-row__value fw-600">{{ $dispute->employmentRecord->employer->company_name ?? '—' }} </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Job Title</span>
                            <span class="detail-row__value">{{ $dispute->employmentRecord->job_title ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Department</span>
                            <span class="detail-row__value">{{ $dispute->employmentRecord->department ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Start Date</span>
                            <span class="detail-row__value">
                                {{ $dispute->employmentRecord->start_date
                                    ? \Carbon\Carbon::parse($dispute->employmentRecord->start_date)->format('d M Y')
                                    : '—' }}
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">End Date</span>
                            <span class="detail-row__value">
                                {{ $dispute->employmentRecord->end_date
                                    ? \Carbon\Carbon::parse($dispute->employmentRecord->end_date)->format('d M Y')
                                    : 'Currently Employed' }}
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row__label">Status</span>
                            <span class="detail-row__value">
                                @php $es = $dispute->employmentRecord->employment_status ?? null; @endphp
                                @if($es)
                                <span class="badge badge--{{ $es === 'active' ? 'green' : 'red' }}">
                                    {{ ucfirst($es) }}
                                </span>
                                @else
                                —
                                @endif
                            </span>
                        </div>
                        @if($dispute->employmentRecord->exit_reason)
                        <div class="detail-row">
                            <span class="detail-row__label">Exit Reason</span>
                            <span class="detail-row__value">{{ $dispute->employmentRecord->exit_reason }}</span>
                        </div>
                        @endif
                        @if($dispute->employmentRecord->remarks)
                        <div class="detail-row">
                            <span class="detail-row__label">Remarks</span>
                            <span class="detail-row__value">{{ $dispute->employmentRecord->remarks }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="right-col">

            {{-- Dispute Description --}}
            <div class="info-card">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    Dispute Description
                </div>
                <div class="info-card__body">
                    <p class="dispute-description">{{ $dispute->description ?? 'No description provided.' }}</p>
                </div>
            </div>

            {{-- Evidence --}}
            @if($dispute->evidence)
            <div class="info-card">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    Evidence
                </div>
                <div class="info-card__body">
                    @php
                    $ext = pathinfo($dispute->evidence, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']);
                    @endphp
                    @if($isImage)
                    <img
                        src="{{ asset('storage/' . $dispute->evidence) }}"
                        alt="Evidence"
                        class="evidence-image">
                    @endif
                    <a
                        href="{{ asset('storage/' . $dispute->evidence) }}"
                        target="_blank"
                        class="evidence-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Download / View Evidence File
                    </a>
                </div>
            </div>
            @endif

            {{-- Update Status --}}
            <div class="info-card info-card--action">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                    </svg>
                    Update Status
                </div>
                <div class="info-card__body">
                    <form action="{{ route('admin.disputes.status', $dispute) }}" method="POST" class="status-form">
                        @csrf
                        @method('PATCH')
                        <div class="status-form__field">
                            <label class="form-label" for="status">Change dispute status</label>
                            <select name="status" id="status" class="form-select form-select--full">
                                <option value="pending" {{ $dispute->status === 'pending'      ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ $dispute->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="resolved" {{ $dispute->status === 'resolved'     ? 'selected' : '' }}>Resolved</option>
                                <option value="rejected" {{ $dispute->status === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn--full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            Save Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="info-card">
                <div class="info-card__header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    Timeline
                </div>
                <div class="info-card__body">
                    <div class="timeline">
                        <div class="timeline__item timeline__item--active">
                            <div class="timeline__dot"></div>
                            <div class="timeline__content">
                                <div class="timeline__title">Dispute Filed</div>
                                <div class="timeline__date">{{ $dispute->created_at->format('d M Y, g:i A') }}</div>
                            </div>
                        </div>
                        @if($dispute->updated_at->ne($dispute->created_at))
                        <div class="timeline__item">
                            <div class="timeline__dot timeline__dot--gray"></div>
                            <div class="timeline__content">
                                <div class="timeline__title">Last Updated</div>
                                <div class="timeline__date">{{ $dispute->updated_at->format('d M Y, g:i A') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


@endsection