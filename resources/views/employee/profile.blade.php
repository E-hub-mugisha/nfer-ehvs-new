@extends('layouts.app')

@section('content')
@section('title', $employee->first_name . ' ' . $employee->last_name . ' — Profile')

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        background: #f4f4f1;
        color: #111;
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    a {
        color: #185FA5;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* ── Layout ── */
    .page {
        max-width: 780px;
        margin: 0 auto;
    }

    /* ── Header card ── */
    .header-card {
        background: #fff;
        border: 0.5px solid #e0e0dc;
        border-radius: 14px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 1rem;
    }

    .avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #B5D4F4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 600;
        color: #0C447C;
        flex-shrink: 0;
        border: 2.5px solid #85B7EB;
        letter-spacing: 1px;
    }

    .header-info {
        flex: 1;
    }

    .header-info h1 {
        font-size: 22px;
        font-weight: 600;
        color: #111;
        line-height: 1.25;
    }

    .header-info .subtitle {
        font-size: 14px;
        color: #555;
        margin-top: 4px;
    }

    .badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .badge-active {
        background: #EAF3DE;
        color: #3B6D11;
    }

    .badge-active .badge-dot {
        background: #639922;
    }

    .badge-inactive {
        background: #F1EFE8;
        color: #5F5E5A;
    }

    .badge-inactive .badge-dot {
        background: #888780;
    }

    .badge-pending {
        background: #FAEEDA;
        color: #633806;
    }

    .badge-pending .badge-dot {
        background: #EF9F27;
    }

    .badge-approved {
        background: #EAF3DE;
        color: #3B6D11;
    }

    .badge-approved .badge-dot {
        background: #639922;
    }

    .badge-rejected {
        background: #FCEBEB;
        color: #791F1F;
    }

    .badge-rejected .badge-dot {
        background: #E24B4A;
    }

    .badge-open {
        background: #FAECE7;
        color: #712B13;
    }

    .badge-open .badge-dot {
        background: #D85A30;
    }

    .badge-resolved {
        background: #EAF3DE;
        color: #3B6D11;
    }

    .badge-resolved .badge-dot {
        background: #639922;
    }

    .badge-under-review {
        background: #E6F1FB;
        color: #0C447C;
    }

    .badge-under-review .badge-dot {
        background: #378ADD;
    }

    .badge-neutral {
        background: #f3f3f0;
        color: #555;
        border: 0.5px solid #e0e0dc;
    }

    /* ── Stats row ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 1rem;
    }

    .stat-card {
        background: #fff;
        border: 0.5px solid #e0e0dc;
        border-radius: 10px;
        padding: 14px 16px;
    }

    .stat-card .stat-label {
        font-size: 12px;
        color: #888;
        margin-bottom: 4px;
    }

    .stat-card .stat-value {
        font-size: 22px;
        font-weight: 600;
        color: #111;
    }

    /* ── Tabs ── */
    .tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid #e0e0dc;
        margin-bottom: 1rem;
        background: #fff;
        border-radius: 12px 12px 0 0;
        padding: 0 1rem;
        border: 0.5px solid #e0e0dc;
        border-bottom: none;
    }

    .tab-btn {
        font-size: 13px;
        font-weight: 500;
        padding: 12px 16px;
        color: #888;
        background: none;
        border: none;
        cursor: pointer;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -1px;
        transition: color 0.15s;
    }

    .tab-btn:hover {
        color: #111;
    }

    .tab-btn.active {
        color: #185FA5;
        border-bottom-color: #185FA5;
    }

    /* ── Section card ── */
    .section-card {
        background: #fff;
        border: 0.5px solid #e0e0dc;
        border-top: none;
        border-radius: 0 0 12px 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    /* tab panels */
    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    /* ── Info grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .info-cell {
        display: flex;
        flex-direction: column;
        padding: 10px 0;
        border-bottom: 0.5px solid #efefed;
    }

    .info-cell:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .info-key {
        font-size: 11px;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 3px;
    }

    .info-val {
        font-size: 13px;
        color: #111;
    }

    /* ── Employment timeline ── */
    .emp-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 13px 0;
        border-bottom: 0.5px solid #efefed;
    }

    .emp-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .timeline-dot.active {
        background: #5DCAA5;
        border: 2px solid #0F6E56;
    }

    .timeline-dot.inactive {
        background: #B4B2A9;
        border: 2px solid #888780;
    }

    .emp-body {
        flex: 1;
    }

    .emp-title {
        font-size: 14px;
        font-weight: 600;
        color: #111;
    }

    .emp-org {
        font-size: 13px;
        color: #555;
        margin-top: 2px;
    }

    .emp-dates {
        font-size: 12px;
        color: #888;
        margin-top: 4px;
    }

    .emp-remark {
        font-size: 12px;
        color: #aaa;
        margin-top: 3px;
        font-style: italic;
    }

    /* ── Transfer items ── */
    .transfer-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 0.5px solid #efefed;
    }

    .transfer-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .transfer-body {
        flex: 1;
    }

    .transfer-title {
        font-size: 14px;
        font-weight: 600;
        color: #111;
    }

    .transfer-meta {
        font-size: 12px;
        color: #555;
        margin-top: 3px;
    }

    .transfer-reason {
        font-size: 12px;
        color: #A32D2D;
        margin-top: 3px;
    }

    /* ── Dispute items ── */
    .dispute-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 0.5px solid #efefed;
    }

    .dispute-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .dispute-body {
        flex: 1;
    }

    .dispute-desc {
        font-size: 13px;
        color: #111;
    }

    .dispute-meta {
        font-size: 12px;
        color: #888;
        margin-top: 3px;
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 28px;
        text-align: center;
        font-size: 13px;
        color: #aaa;
    }

    @media (max-width: 560px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-cell:nth-last-child(-n+2) {
            border-bottom: 0.5px solid #efefed;
        }

        .info-cell:last-child {
            border-bottom: none;
        }

        .header-card {
            flex-direction: column;
        }
    }
</style>

<div class="container">

    {{-- ── Header ── --}}
    <div class="header-card">
        <div class="avatar">
            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
        </div>
        <div class="header-info">
            <h1>{{ $employee->first_name }} {{ $employee->last_name }}</h1>

            @php
            $current = $employee->employmentRecords->firstWhere('employment_status', 'active');
            @endphp

            @if($current)
            <div class="subtitle">{{ $current->job_title }} &nbsp;·&nbsp; {{ $current->department }}</div>
            @endif

            <div class="badge-row">
                @if($current)
                <span class="badge badge-active">
                    <span class="badge-dot"></span> Active
                </span>
                @else
                <span class="badge badge-inactive">
                    <span class="badge-dot"></span> Inactive
                </span>
                @endif

                <span class="badge badge-neutral">
                    NID: {{ $employee->nid }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Employment records</div>
            <div class="stat-value">{{ $employee->employmentRecords->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Transfer requests</div>
            <div class="stat-value">{{ $employee->transferRequests->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Disputes</div>
            <div class="stat-value">{{ $employee->disputes->count() }}</div>
        </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'info')">Personal info</button>
        <button class="tab-btn" onclick="switchTab(event, 'employment')">Employment</button>
        <button class="tab-btn" onclick="switchTab(event, 'transfers')">Transfers</button>
        <button class="tab-btn" onclick="switchTab(event, 'disputes')">Disputes</button>
    </div>

    <div class="section-card">

        {{-- ── Personal info tab ── --}}
        <div id="tab-info" class="tab-panel active">
            <div class="info-grid">
                <div class="info-cell">
                    <span class="info-key">First name</span>
                    <span class="info-val">{{ $employee->first_name }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Last name</span>
                    <span class="info-val">{{ $employee->last_name }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Gender</span>
                    <span class="info-val">{{ ucfirst($employee->gender) }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Date of birth</span>
                    <span class="info-val">
                        {{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}
                    </span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Phone</span>
                    <span class="info-val">{{ $employee->phone }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Email</span>
                    <span class="info-val">
                        <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                    </span>
                </div>
                <div class="info-cell">
                    <span class="info-key">District</span>
                    <span class="info-val">{{ $employee->district }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-key">Sector</span>
                    <span class="info-val">{{ $employee->sector }}</span>
                </div>
            </div>
        </div>

        {{-- ── Employment tab ── --}}
        <div id="tab-employment" class="tab-panel">
            @forelse($employee->employmentRecords as $record)
            <div class="emp-item">
                <div class="timeline-dot {{ $record->employment_status === 'active' ? 'active' : 'inactive' }}"></div>
                <div class="emp-body">
                    <div class="emp-title">{{ $record->job_title }}</div>
                    <div class="emp-org">
                        {{ $record->employer->name ?? '—' }}
                        @if($record->department) &nbsp;·&nbsp; {{ $record->department }} @endif
                    </div>
                    <div class="emp-dates">
                        {{ \Carbon\Carbon::parse($record->start_date)->format('M Y') }}
                        —
                        {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M Y') : 'Present' }}
                        @if($record->exit_reason) &nbsp;·&nbsp; {{ $record->exit_reason }} @endif
                    </div>
                    @if($record->remarks)
                    <div class="emp-remark">{{ $record->remarks }}</div>
                    @endif
                </div>
                @php
                $statusClass = $record->employment_status === 'active' ? 'badge-active' : 'badge-inactive';
                @endphp
                <span class="badge {{ $statusClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($record->employment_status) }}
                </span>
            </div>
            @empty
            <div class="empty-state">No employment records found.</div>
            @endforelse
        </div>

        {{-- ── Transfers tab ── --}}
        <div id="tab-transfers" class="tab-panel">
            @forelse($employee->transferRequests as $transfer)
            @php
            $tClass = match(strtolower($transfer->status)) {
            'pending' => 'badge-pending',
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            default => 'badge-neutral',
            };
            @endphp
            <div class="transfer-item">
                <div class="transfer-body">
                    <div class="transfer-title">
                        {{ $transfer->proposed_job_title }}
                        @if($transfer->proposed_department)
                        &nbsp;·&nbsp; {{ $transfer->proposed_department }}
                        @endif
                    </div>
                    <div class="transfer-meta">
                        Requested by {{ $transfer->requestingEmployer->name ?? '—' }}
                        — from {{ $transfer->currentEmployer->name ?? '—' }}
                    </div>
                    @if($transfer->proposed_start_date)
                    <div class="transfer-meta">
                        Proposed start:
                        {{ \Carbon\Carbon::parse($transfer->proposed_start_date)->format('d M Y') }}
                    </div>
                    @endif
                    @if($transfer->rejection_reason)
                    <div class="transfer-reason">
                        Rejection reason: {{ $transfer->rejection_reason }}
                    </div>
                    @endif
                </div>
                <span class="badge {{ $tClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($transfer->status) }}
                </span>
            </div>
            @empty
            <div class="empty-state">No transfer requests found.</div>
            @endforelse
        </div>

        {{-- ── Disputes tab ── --}}
        <div id="tab-disputes" class="tab-panel">
            @forelse($employee->disputes as $dispute)
            @php
            $dKey = strtolower(str_replace(' ', '-', $dispute->status));
            $dClass = match($dKey) {
            'open' => 'badge-open',
            'resolved' => 'badge-resolved',
            'under-review' => 'badge-under-review',
            default => 'badge-neutral',
            };
            @endphp
            <div class="dispute-item">
                <div class="dispute-body">
                    <div class="dispute-desc">{{ $dispute->description }}</div>
                    <div class="dispute-meta">
                        Employment record #{{ $dispute->employment_record_id }}
                        @if($dispute->evidence)
                        &nbsp;·&nbsp; Evidence: {{ $dispute->evidence }}
                        @endif
                    </div>
                </div>
                <span class="badge {{ $dClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($dispute->status) }}
                </span>
            </div>
            @empty
            <div class="empty-state">No disputes on record.</div>
            @endforelse
        </div>

    </div>{{-- /section-card --}}
</div>{{-- /page --}}

<script>
    function switchTab(e, id) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        e.currentTarget.classList.add('active');
        document.getElementById('tab-' + id).classList.add('active');
    }
</script>
@endsection