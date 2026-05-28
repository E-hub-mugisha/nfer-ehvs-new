{{-- resources/views/government/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard — NFER-EHVS')

@section('content')
<style>
    /* ── Design tokens ───────────────────────────────────────────────────── */
    :root {
        --navy:        #0d1b3e;
        --navy-mid:    #162552;
        --navy-light:  #1e3370;
        --gold:        #c9a84c;
        --gold-light:  #e8c878;
        --gold-pale:   rgba(201,168,76,.12);
        --surface:     #f5f6fa;
        --card:        #ffffff;
        --border:      #e4e7f0;
        --text:        #1a2340;
        --muted:       #6b7a9e;
        --success:     #16a34a;
        --warning:     #d97706;
        --danger:      #dc2626;
        --info:        #0369a1;
        --radius:      14px;
        --radius-sm:   8px;
        --shadow:      0 2px 16px rgba(13,27,62,.07);
        --shadow-md:   0 4px 24px rgba(13,27,62,.11);
    }

    /* ── Base ────────────────────────────────────────────────────────────── */
    body { background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }

    /* ── Page header ─────────────────────────────────────────────────────── */
    .dash-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        border-radius: var(--radius);
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .dash-header::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .dash-header .title {
        font-family: 'Syne', sans-serif;
        font-size: 1.65rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
        letter-spacing: -.3px;
    }
    .dash-header .subtitle { color: rgba(255,255,255,.6); font-size: .875rem; margin: 0; }
    .dash-header .badge-live {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(201,168,76,.2); border: 1px solid rgba(201,168,76,.35);
        color: var(--gold-light); border-radius: 20px;
        padding: 4px 12px; font-size: .75rem; font-weight: 600;
    }
    .dash-header .badge-live span {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--gold-light);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%,100%{ opacity:1; transform:scale(1); }
        50%{ opacity:.5; transform:scale(1.3); }
    }

    /* ── KPI Cards ───────────────────────────────────────────────────────── */
    .kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; margin-bottom: 28px; }
    @media(max-width:992px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
    @media(max-width:576px){ .kpi-grid{ grid-template-columns:1fr; } }

    .kpi-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px 24px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .kpi-card::after {
        content:'';
        position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--accent-bar, var(--navy));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    .kpi-card.gold::after   { --accent-bar: var(--gold); }
    .kpi-card.green::after  { --accent-bar: var(--success); }
    .kpi-card.danger::after { --accent-bar: var(--danger); }
    .kpi-card.info::after   { --accent-bar: var(--info); }

    .kpi-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; margin-bottom: 14px;
    }
    .kpi-card.gold   .kpi-icon { background: rgba(201,168,76,.12); color: var(--gold); }
    .kpi-card.green  .kpi-icon { background: rgba(22,163,74,.1);   color: var(--success); }
    .kpi-card.danger .kpi-icon { background: rgba(220,38,38,.1);   color: var(--danger); }
    .kpi-card.info   .kpi-icon { background: rgba(3,105,161,.1);   color: var(--info); }
    .kpi-card        .kpi-icon { background: rgba(13,27,62,.07);   color: var(--navy); }

    .kpi-value {
        font-family: 'Syne', sans-serif;
        font-size: 2rem; font-weight: 800;
        line-height: 1; color: var(--text);
        margin-bottom: 4px;
    }
    .kpi-label { font-size: .78rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
    .kpi-delta {
        position: absolute; top: 18px; right: 20px;
        font-size: .75rem; font-weight: 700;
        display: flex; align-items: center; gap: 3px;
    }
    .kpi-delta.up   { color: var(--success); }
    .kpi-delta.down { color: var(--danger); }
    .kpi-sub { font-size: .78rem; color: var(--muted); margin-top: 6px; }
    .kpi-sub strong { color: var(--text); }

    /* ── Charts grid ─────────────────────────────────────────────────────── */
    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
    .charts-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media(max-width:1100px){ .charts-grid{ grid-template-columns:1fr; } .charts-grid-3{ grid-template-columns:1fr 1fr; } }
    @media(max-width:650px) { .charts-grid-3{ grid-template-columns:1fr; } }

    .chart-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
    }
    .chart-title {
        font-family: 'Syne', sans-serif;
        font-size: .95rem; font-weight: 700;
        color: var(--text); margin-bottom: 4px;
    }
    .chart-sub { font-size: .78rem; color: var(--muted); margin-bottom: 20px; }
    .chart-wrap { position: relative; }

    /* ── Status pills ────────────────────────────────────────────────────── */
    .pill {
        display: inline-block;
        padding: 3px 10px; border-radius: 20px;
        font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px;
    }
    .pill-pending  { background: rgba(217,119,6,.1);  color: #b45309; }
    .pill-approved { background: rgba(22,163,74,.1);  color: #15803d; }
    .pill-rejected { background: rgba(220,38,38,.1);  color: #b91c1c; }
    .pill-resolved { background: rgba(3,105,161,.1);  color: #0369a1; }
    .pill-active   { background: rgba(22,163,74,.1);  color: #15803d; }
    .pill-inactive { background: rgba(107,114,158,.1);color: var(--muted); }
    .pill-terminated{ background: rgba(220,38,38,.1); color: #b91c1c; }

    /* ── Data tables ─────────────────────────────────────────────────────── */
    .table-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .table-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-header .title { font-family: 'Syne',sans-serif; font-size: .95rem; font-weight: 700; color: var(--text); margin: 0; }
    .table-card-header .view-all {
        font-size: .78rem; font-weight: 600; color: var(--gold);
        text-decoration: none; transition: opacity .15s;
    }
    .table-card-header .view-all:hover { opacity: .75; }

    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table thead th {
        padding: 10px 16px;
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: var(--muted);
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .dash-table tbody td { padding: 13px 16px; font-size: .84rem; border-bottom: 1px solid #f0f2f8; vertical-align: middle; }
    .dash-table tbody tr:last-child td { border-bottom: none; }
    .dash-table tbody tr:hover td { background: #fafbfd; }

    .emp-cell { display: flex; align-items: center; gap: 10px; }
    .emp-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--navy); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; flex-shrink: 0;
    }
    .emp-name { font-weight: 600; color: var(--text); }
    .emp-id   { font-size: .72rem; color: var(--muted); }

    /* ── Top employers bar ───────────────────────────────────────────────── */
    .employer-bar { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f0f2f8; }
    .employer-bar:last-child { border-bottom: none; }
    .employer-bar .name { font-size: .84rem; font-weight: 600; color: var(--text); min-width: 150px; }
    .employer-bar .bar-track { flex: 1; height: 7px; background: #eef0f8; border-radius: 4px; overflow: hidden; }
    .employer-bar .bar-fill  { height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--navy), var(--gold)); transition: width .8s cubic-bezier(.4,0,.2,1); }
    .employer-bar .count { font-size: .8rem; font-weight: 700; color: var(--navy); min-width: 28px; text-align: right; }

    /* ── Scrollable table wrapper ─────────────────────────────────────────── */
    .table-scroll { overflow-x: auto; }

    /* ── Fade-in animation ───────────────────────────────────────────────── */
    .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp .45s ease forwards; }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
    .delay-1{ animation-delay:.05s; } .delay-2{ animation-delay:.1s; }
    .delay-3{ animation-delay:.15s; } .delay-4{ animation-delay:.2s; }
    .delay-5{ animation-delay:.25s; } .delay-6{ animation-delay:.3s; }
    .delay-7{ animation-delay:.35s; } .delay-8{ animation-delay:.4s; }
</style>

<div class="container-fluid px-4 py-4">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="dash-header fade-up">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="title">System Dashboard</h1>
                <p class="subtitle">National Employment History Verification System — Overview</p>
            </div>
            <div class="d-flex align-items-center gap-3 mt-1">
                <span class="badge-live"><span></span>Live Data</span>
                <span style="color:rgba(255,255,255,.55);font-size:.8rem;">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- ── KPI Cards ────────────────────────────────────────────────────── --}}
    <div class="kpi-grid">
        {{-- Employees --}}
        <div class="kpi-card fade-up delay-1">
            <div class="kpi-delta {{ $employeeGrowth['direction'] }}">
                <i class="fas fa-arrow-{{ $employeeGrowth['direction'] == 'up' ? 'up' : 'down' }}"></i>
                {{ $employeeGrowth['value'] }}%
            </div>
            <div class="kpi-icon"><i class="fas fa-users"></i></div>
            <div class="kpi-value">{{ number_format($totalEmployees) }}</div>
            <div class="kpi-label">Total Employees</div>
            <div class="kpi-sub">30-day growth vs prior period</div>
        </div>

        {{-- Employers --}}
        <div class="kpi-card gold fade-up delay-2">
            <div class="kpi-delta {{ $employerGrowth['direction'] }}">
                <i class="fas fa-arrow-{{ $employerGrowth['direction'] == 'up' ? 'up' : 'down' }}"></i>
                {{ $employerGrowth['value'] }}%
            </div>
            <div class="kpi-icon"><i class="fas fa-building"></i></div>
            <div class="kpi-value">{{ number_format($totalEmployers) }}</div>
            <div class="kpi-label">Registered Employers</div>
            <div class="kpi-sub">30-day growth vs prior period</div>
        </div>

        {{-- Active Employments --}}
        <div class="kpi-card green fade-up delay-3">
            <div class="kpi-delta {{ $recordGrowth['direction'] }}">
                <i class="fas fa-arrow-{{ $recordGrowth['direction'] == 'up' ? 'up' : 'down' }}"></i>
                {{ $recordGrowth['value'] }}%
            </div>
            <div class="kpi-icon"><i class="fas fa-id-badge"></i></div>
            <div class="kpi-value">{{ number_format($activeEmployments) }}</div>
            <div class="kpi-label">Active Employments</div>
            <div class="kpi-sub">of <strong>{{ number_format($totalEmploymentRecords) }}</strong> total records</div>
        </div>

        {{-- Pending Disputes --}}
        <div class="kpi-card danger fade-up delay-4">
            <div class="kpi-delta {{ $disputeGrowth['direction'] }}">
                <i class="fas fa-arrow-{{ $disputeGrowth['direction'] == 'up' ? 'up' : 'down' }}"></i>
                {{ $disputeGrowth['value'] }}%
            </div>
            <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="kpi-value">{{ number_format($pendingDisputes) }}</div>
            <div class="kpi-label">Pending Disputes</div>
            <div class="kpi-sub">of <strong>{{ number_format($totalDisputes) }}</strong> total disputes</div>
        </div>
    </div>

    {{-- Row 2 KPIs --}}
    <div class="kpi-grid" style="grid-template-columns:repeat(4,1fr); margin-bottom:28px;">
        <div class="kpi-card info fade-up delay-1">
            <div class="kpi-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="kpi-value">{{ number_format($totalTransferRequests) }}</div>
            <div class="kpi-label">Transfer Requests</div>
            <div class="kpi-sub"><strong>{{ number_format($pendingTransfers) }}</strong> pending review</div>
        </div>
        <div class="kpi-card fade-up delay-2">
            <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
            <div class="kpi-value">{{ number_format($transfersByStatus['approved'] ?? 0) }}</div>
            <div class="kpi-label">Approved Transfers</div>
            <div class="kpi-sub">All time</div>
        </div>
        <div class="kpi-card gold fade-up delay-3">
            <div class="kpi-icon"><i class="fas fa-folder-open"></i></div>
            <div class="kpi-value">{{ number_format($disputesByStatus['resolved'] ?? 0) }}</div>
            <div class="kpi-label">Resolved Disputes</div>
            <div class="kpi-sub">All time</div>
        </div>
        <div class="kpi-card green fade-up delay-4">
            <div class="kpi-icon"><i class="fas fa-percent"></i></div>
            @php
                $resolutionRate = $totalDisputes > 0
                    ? round((($disputesByStatus['resolved'] ?? 0) / $totalDisputes) * 100, 1)
                    : 0;
            @endphp
            <div class="kpi-value">{{ $resolutionRate }}%</div>
            <div class="kpi-label">Resolution Rate</div>
            <div class="kpi-sub">Disputes closed vs total</div>
        </div>
    </div>

    {{-- ── Line Chart: Monthly Trends ──────────────────────────────────── --}}
    <div class="charts-grid fade-up delay-2">
        <div class="chart-card">
            <div class="chart-title">Registration Trends</div>
            <div class="chart-sub">New employees & employment records — last 12 months</div>
            <div class="chart-wrap"><canvas id="trendChart" height="120"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title">Dispute Status</div>
            <div class="chart-sub">Current breakdown by status</div>
            <div class="chart-wrap d-flex align-items-center justify-content-center" style="min-height:220px;">
                <canvas id="disputeChart" style="max-width:220px;max-height:220px;"></canvas>
            </div>
        </div>
    </div>

    {{-- ── 3-col Charts ─────────────────────────────────────────────────── --}}
    <div class="charts-grid-3 fade-up delay-3">
        {{-- Transfer Requests by Status --}}
        <div class="chart-card">
            <div class="chart-title">Transfer Requests</div>
            <div class="chart-sub">By current status</div>
            <div class="chart-wrap"><canvas id="transferChart" height="180"></canvas></div>
        </div>

        {{-- Employment Status --}}
        <div class="chart-card">
            <div class="chart-title">Employment Records</div>
            <div class="chart-sub">By employment status</div>
            <div class="chart-wrap"><canvas id="recordChart" height="180"></canvas></div>
        </div>

        {{-- Top Employers --}}
        <div class="chart-card">
            <div class="chart-title">Top Employers</div>
            <div class="chart-sub">By active headcount</div>
            @php $maxCount = collect($topEmployerCounts)->max() ?: 1; @endphp
            <div style="padding-top:6px;">
                @foreach($topEmployerLabels as $i => $label)
                <div class="employer-bar">
                    <div class="name" title="{{ $label }}">
                        {{ Str::limit($label, 22) }}
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:{{ round(($topEmployerCounts[$i] / $maxCount) * 100) }}%"></div>
                    </div>
                    <div class="count">{{ $topEmployerCounts[$i] }}</div>
                </div>
                @endforeach
                @if(empty($topEmployerLabels))
                    <p class="text-center" style="color:var(--muted);font-size:.85rem;padding:20px 0;">No data yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Recent Disputes Table ────────────────────────────────────────── --}}
    <div class="table-card fade-up delay-5">
        <div class="table-card-header">
            <h6 class="title"><i class="fas fa-exclamation-circle me-2" style="color:var(--danger)"></i>Recent Disputes</h6>
            <a href="{{ route('government.disputes.index') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="table-scroll">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Employer</th>
                        <th>Job Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDisputes as $dispute)
                    <tr>
                        <td style="color:var(--muted);font-size:.8rem;">{{ $dispute->id }}</td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar">
                                    {{ strtoupper(substr($dispute->employee?->first_name ?? 'N', 0, 1) . substr($dispute->employee?->last_name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="emp-name">{{ $dispute->employee?->full_name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $dispute->employmentRecord?->employer?->company_name ?? '—' }}</td>
                        <td style="color:var(--muted);">{{ $dispute->employmentRecord?->job_title ?? '—' }}</td>
                        <td>{{ Str::limit($dispute->description, 55) }}</td>
                        <td>
                            <span class="pill pill-{{ strtolower($dispute->status) }}">{{ $dispute->status }}</span>
                        </td>
                        <td style="color:var(--muted);font-size:.8rem;">{{ $dispute->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4" style="color:var(--muted);">No disputes recorded yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Recent Transfer Requests Table ──────────────────────────────── --}}
    <div class="table-card fade-up delay-6">
        <div class="table-card-header">
            <h6 class="title"><i class="fas fa-exchange-alt me-2" style="color:var(--info)"></i>Recent Transfer Requests</h6>
            <a href="{{ route('government.transfer-requests.index') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="table-scroll">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>From Employer</th>
                        <th>To Employer</th>
                        <th>Proposed Title</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransfers as $tr)
                    <tr>
                        <td style="color:var(--muted);font-size:.8rem;">{{ $tr->id }}</td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar">
                                    {{ strtoupper(substr($tr->employee?->first_name ?? 'N', 0, 1) . substr($tr->employee?->last_name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="emp-name">{{ $tr->employee?->full_name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ Str::limit($tr->currentEmployer?->company_name ?? '—', 25) }}</td>
                        <td>{{ Str::limit($tr->requestingEmployer?->company_name ?? '—', 25) }}</td>
                        <td style="color:var(--muted);">{{ $tr->proposed_job_title ?? '—' }}</td>
                        <td>{{ $tr->proposed_start_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <span class="pill pill-{{ strtolower($tr->status) }}">{{ $tr->status }}</span>
                        </td>
                        <td style="color:var(--muted);font-size:.8rem;">{{ $tr->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4" style="color:var(--muted);">No transfer requests yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#6b7a9e';

const NAVY      = '#0d1b3e';
const NAVY_MID  = '#162552';
const NAVY_LT   = '#1e3370';
const GOLD      = '#c9a84c';
const GOLD_LT   = '#e8c878';
const SUCCESS   = '#16a34a';
const DANGER    = '#dc2626';
const WARNING   = '#d97706';
const INFO      = '#0369a1';

/* ── 1. Trend Line Chart ───────────────────────────────────────────────── */
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($monthlyLabels),
        datasets: [
            {
                label: 'New Employees',
                data: @json($monthlyCounts),
                borderColor: GOLD,
                backgroundColor: 'rgba(201,168,76,.1)',
                borderWidth: 2.5,
                pointBackgroundColor: GOLD,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: .4,
                fill: true,
            },
            {
                label: 'Employment Records',
                data: @json($monthlyRecordCounts),
                borderColor: NAVY_LT,
                backgroundColor: 'rgba(30,51,112,.08)',
                borderWidth: 2.5,
                pointBackgroundColor: NAVY_LT,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: .4,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 18 } },
            tooltip: { backgroundColor: NAVY, titleColor: '#fff', bodyColor: 'rgba(255,255,255,.75)', padding: 12, cornerRadius: 8 }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#eef0f8' }, ticks: { font: { size: 11 } }, beginAtZero: true }
        }
    }
});

/* ── 2. Dispute Doughnut ───────────────────────────────────────────────── */
const disputeData = @json($disputesByStatus);
const disputeLabels = Object.keys(disputeData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
const disputeCounts = Object.values(disputeData);
const disputeColors = { Pending: WARNING, Approved: SUCCESS, Rejected: DANGER, Resolved: INFO };

new Chart(document.getElementById('disputeChart'), {
    type: 'doughnut',
    data: {
        labels: disputeLabels,
        datasets: [{
            data: disputeCounts,
            backgroundColor: disputeLabels.map(l => disputeColors[l] ?? '#94a3b8'),
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 14, font: { size: 12 } } },
            tooltip: { backgroundColor: NAVY, padding: 12, cornerRadius: 8 }
        }
    }
});

/* ── 3. Transfer Requests Bar ──────────────────────────────────────────── */
const transferData   = @json($transfersByStatus);
const transferLabels = Object.keys(transferData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
const transferCounts = Object.values(transferData);
const transferColors = { Pending: WARNING, Approved: SUCCESS, Rejected: DANGER };

new Chart(document.getElementById('transferChart'), {
    type: 'bar',
    data: {
        labels: transferLabels,
        datasets: [{
            label: 'Transfers',
            data: transferCounts,
            backgroundColor: transferLabels.map(l => (transferColors[l] ?? INFO) + 'cc'),
            borderColor:     transferLabels.map(l => transferColors[l] ?? INFO),
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#eef0f8' }, beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

/* ── 4. Employment Records Bar ─────────────────────────────────────────── */
const recordData   = @json($recordsByStatus);
const recordLabels = Object.keys(recordData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
const recordCounts = Object.values(recordData);
const recordColors = { Active: SUCCESS, Inactive: '#94a3b8', Terminated: DANGER };

new Chart(document.getElementById('recordChart'), {
    type: 'bar',
    data: {
        labels: recordLabels,
        datasets: [{
            label: 'Records',
            data: recordCounts,
            backgroundColor: recordLabels.map(l => (recordColors[l] ?? NAVY_LT) + 'cc'),
            borderColor:     recordLabels.map(l => recordColors[l] ?? NAVY_LT),
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#eef0f8' }, beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endsection