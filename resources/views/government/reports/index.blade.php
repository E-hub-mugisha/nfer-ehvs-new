{{-- resources/views/government/reports/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Reports & Analytics — NFER-EHVS')

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
        --shadow-md:  0 6px 32px rgba(13,27,62,.12);
    }
    body { background:var(--surface); font-family:'Plus Jakarta Sans',sans-serif; color:var(--text); }

    /* ── Page Header ──────────────────────────────────────────────────── */
    .page-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        border-radius: var(--radius); padding:26px 30px; margin-bottom:24px;
        position:relative; overflow:hidden;
    }
    .page-header::before {
        content:''; position:absolute; inset:0;
        background:repeating-linear-gradient(45deg, rgba(255,255,255,.02) 0px, rgba(255,255,255,.02) 1px, transparent 1px, transparent 12px);
    }
    .page-header h1 { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 4px; }
    .page-header p  { color:rgba(255,255,255,.6); font-size:.85rem; margin:0; }

    /* ── Date Range Filter ────────────────────────────────────────────── */
    .range-bar {
        background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
        padding:16px 22px; margin-bottom:24px;
        display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap;
        box-shadow:var(--shadow);
    }
    .range-bar label  { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); display:block; margin-bottom:5px; }
    .range-ctrl { padding:8px 12px; border-radius:8px; border:1.5px solid var(--border); font-size:.84rem; font-family:inherit; color:var(--text); outline:none; transition:border-color .2s; }
    .range-ctrl:focus { border-color:var(--gold); }
    .btn-apply { padding:9px 22px; border-radius:8px; border:none; background:var(--navy); color:#fff; font-size:.84rem; font-weight:700; cursor:pointer; transition:background .2s; }
    .btn-apply:hover { background:var(--navy-light); }
    .range-badge {
        margin-left:auto; align-self:center;
        background:var(--gold-pale); border:1px solid rgba(201,168,76,.3);
        color:#92700a; border-radius:20px; padding:6px 16px; font-size:.8rem; font-weight:700;
    }

    /* ── Export Strip ─────────────────────────────────────────────────── */
    .export-strip {
        background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
        padding:14px 22px; margin-bottom:24px;
        display:flex; align-items:center; gap:10px; flex-wrap:wrap;
        box-shadow:var(--shadow);
    }
    .export-strip .label { font-size:.8rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-right:4px; }
    .btn-export {
        display:inline-flex; align-items:center; gap:7px;
        padding:7px 16px; border-radius:8px; font-size:.8rem; font-weight:700;
        text-decoration:none; transition:all .2s; border:1.5px solid;
    }
    .btn-export-emp  { background:rgba(22,163,74,.08);  color:#15803d; border-color:rgba(22,163,74,.3); }
    .btn-export-emp:hover  { background:rgba(22,163,74,.15); }
    .btn-export-er   { background:rgba(3,105,161,.08);  color:#0369a1; border-color:rgba(3,105,161,.3); }
    .btn-export-er:hover   { background:rgba(3,105,161,.15); }
    .btn-export-tr   { background:var(--gold-pale);     color:#92700a; border-color:rgba(201,168,76,.3); }
    .btn-export-tr:hover   { background:rgba(201,168,76,.18); }
    .btn-export-disp { background:rgba(220,38,38,.07);  color:#b91c1c; border-color:rgba(220,38,38,.25); }
    .btn-export-disp:hover { background:rgba(220,38,38,.13); }

    /* ── KPI Strip ────────────────────────────────────────────────────── */
    .kpi-strip { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:24px; }
    @media(max-width:1100px){ .kpi-strip{ grid-template-columns:repeat(3,1fr); } }
    @media(max-width:640px) { .kpi-strip{ grid-template-columns:repeat(2,1fr); } }

    .kpi-mini {
        background:var(--card); border:1px solid var(--border); border-radius:12px;
        padding:18px 20px; box-shadow:var(--shadow); position:relative; overflow:hidden;
        transition:transform .2s;
    }
    .kpi-mini:hover { transform:translateY(-2px); }
    .kpi-mini::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--kpi-color, var(--navy)); border-radius:12px 12px 0 0; }
    .kpi-mini .kpi-val { font-family:'Syne',sans-serif; font-size:1.8rem; font-weight:800; line-height:1; margin-bottom:4px; }
    .kpi-mini .kpi-lbl { font-size:.74rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); }
    .kpi-mini .kpi-sub { font-size:.74rem; color:var(--muted); margin-top:5px; }

    /* ── Rate Rings ───────────────────────────────────────────────────── */
    .rate-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .rate-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:20px; box-shadow:var(--shadow); text-align:center; }
    .rate-title { font-family:'Syne',sans-serif; font-size:.85rem; font-weight:700; color:var(--text); margin-bottom:16px; }
    .rate-ring  { position:relative; width:110px; height:110px; margin:0 auto 12px; }
    .rate-ring svg { transform:rotate(-90deg); }
    .rate-ring .pct { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-size:1.4rem; font-weight:800; }
    .rate-sub { font-size:.78rem; color:var(--muted); }

    /* ── Chart Cards ──────────────────────────────────────────────────── */
    .charts-2col { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px; }
    .charts-3col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:20px; }
    .charts-eq   { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    @media(max-width:1050px){ .charts-2col,.charts-eq{ grid-template-columns:1fr; } .charts-3col{ grid-template-columns:1fr 1fr; } }
    @media(max-width:640px) { .charts-3col{ grid-template-columns:1fr; } }

    .chart-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:22px; box-shadow:var(--shadow); }
    .chart-title { font-family:'Syne',sans-serif; font-size:.93rem; font-weight:700; color:var(--text); margin-bottom:2px; }
    .chart-sub   { font-size:.77rem; color:var(--muted); margin-bottom:18px; }

    /* ── District bars ────────────────────────────────────────────────── */
    .dist-row { display:flex; align-items:center; gap:10px; padding:7px 0; border-bottom:1px solid #f0f2f8; }
    .dist-row:last-child { border:none; }
    .dist-name { font-size:.82rem; font-weight:600; color:var(--text); min-width:110px; }
    .dist-track { flex:1; height:6px; background:#eef0f8; border-radius:3px; overflow:hidden; }
    .dist-fill  { height:100%; border-radius:3px; background:linear-gradient(90deg, var(--navy), var(--gold)); transition:width .9s cubic-bezier(.4,0,.2,1); }
    .dist-count { font-size:.78rem; font-weight:700; color:var(--navy); min-width:28px; text-align:right; }

    /* Employer rows */
    .emp-row { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid #f0f2f8; }
    .emp-row:last-child { border:none; }
    .emp-row .rank { width:22px; height:22px; border-radius:50%; background:var(--navy); color:#fff; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .emp-row:nth-child(1) .rank { background:var(--gold); color:var(--navy); }
    .emp-row:nth-child(2) .rank { background:#94a3b8; }
    .emp-row:nth-child(3) .rank { background:#c08060; color:#fff; }
    .emp-row .ename { font-size:.82rem; font-weight:600; color:var(--text); flex:1; }
    .emp-row .ecount{ font-size:.8rem; font-weight:700; color:var(--navy); }

    /* Fade */
    .fade-up { opacity:0; transform:translateY(14px); animation:fadeUp .42s ease forwards; }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
    .d1{animation-delay:.04s} .d2{animation-delay:.09s} .d3{animation-delay:.14s}
    .d4{animation-delay:.19s} .d5{animation-delay:.24s} .d6{animation-delay:.29s}
    .d7{animation-delay:.34s} .d8{animation-delay:.39s}
</style>

<div class="container-fluid px-4 py-4">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="page-header fade-up">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-chart-bar me-2" style="color:var(--gold-light)"></i>Reports & Analytics</h1>
                <p>System-wide employment statistics · Date range: <strong style="color:var(--gold-light);">{{ \Carbon\Carbon::parse($from)->format('d M Y') }}</strong> — <strong style="color:var(--gold-light);">{{ \Carbon\Carbon::parse($to)->format('d M Y') }}</strong></p>
            </div>
        </div>
    </div>

    {{-- ── Date Range ────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('government.reports.index') }}" class="range-bar fade-up d1">
        <div>
            <label>From</label>
            <input type="date" name="from" value="{{ $from }}" class="range-ctrl">
        </div>
        <div>
            <label>To</label>
            <input type="date" name="to" value="{{ $to }}" class="range-ctrl">
        </div>
        <button type="submit" class="btn-apply"><i class="fas fa-sync-alt me-1"></i>Apply Range</button>
        <a href="{{ route('government.reports.index') }}" style="align-self:flex-end;padding:9px 16px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--muted);font-size:.84rem;font-weight:600;text-decoration:none;">Reset</a>
        <span class="range-badge"><i class="fas fa-calendar-alt me-1"></i>
            {{ \Carbon\Carbon::parse($from)->diffInDays(\Carbon\Carbon::parse($to)) + 1 }} days selected
        </span>
    </form>

    {{-- ── Export Buttons ────────────────────────────────────────────────── --}}
    <div class="export-strip fade-up d2">
        <span class="label"><i class="fas fa-download me-1"></i>Export CSV:</span>
        <a href="{{ route('government.reports.export', 'employees') }}?from={{ $from }}&to={{ $to }}" class="btn-export btn-export-emp">
            <i class="fas fa-users"></i>Employees
        </a>
        <a href="{{ route('government.reports.export', 'records') }}?from={{ $from }}&to={{ $to }}" class="btn-export btn-export-er">
            <i class="fas fa-id-badge"></i>Employment Records
        </a>
        <a href="{{ route('government.reports.export', 'transfers') }}?from={{ $from }}&to={{ $to }}" class="btn-export btn-export-tr">
            <i class="fas fa-exchange-alt"></i>Transfer Requests
        </a>
    
    </div>

    {{-- ── KPI Strip ─────────────────────────────────────────────────────── --}}
    <div class="kpi-strip fade-up d2">
        <div class="kpi-mini" style="--kpi-color:var(--navy)">
            <div class="kpi-val">{{ number_format($summary['employees']) }}</div>
            <div class="kpi-lbl">New Employees</div>
            <div class="kpi-sub">In selected range</div>
        </div>
        <div class="kpi-mini" style="--kpi-color:var(--gold)">
            <div class="kpi-val">{{ number_format($summary['employers']) }}</div>
            <div class="kpi-lbl">New Employers</div>
            <div class="kpi-sub">In selected range</div>
        </div>
        <div class="kpi-mini" style="--kpi-color:var(--success)">
            <div class="kpi-val">{{ number_format($summary['records']) }}</div>
            <div class="kpi-lbl">Employment Records</div>
            <div class="kpi-sub">Created in range</div>
        </div>
        <div class="kpi-mini" style="--kpi-color:var(--danger)">
            <div class="kpi-val">{{ number_format($summary['disputes']) }}</div>
            <div class="kpi-lbl">Disputes Filed</div>
            <div class="kpi-sub">In selected range</div>
        </div>
        <div class="kpi-mini" style="--kpi-color:var(--info)">
            <div class="kpi-val">{{ number_format($summary['transfers']) }}</div>
            <div class="kpi-lbl">Transfer Requests</div>
            <div class="kpi-sub">In selected range</div>
        </div>
    </div>

    {{-- ── Main Trend + Rates ────────────────────────────────────────────── --}}
    <div class="charts-2col fade-up d3">
        {{-- Trend Chart --}}
        <div class="chart-card">
            <div class="chart-title">Registration Activity</div>
            <div class="chart-sub">Employees, employers, disputes & transfers over the selected period</div>
            <canvas id="trendChart" height="115"></canvas>
        </div>
        {{-- Resolution & Approval Rings --}}
        <div class="chart-card">
            <div class="chart-title">Performance Rates</div>
            <div class="chart-sub">Key resolution metrics for this period</div>
            <div class="rate-grid">
                
                <div class="rate-card">
                    <div class="rate-title">Transfer Approval</div>
                    <div class="rate-ring">
                        <svg viewBox="0 0 36 36" width="110" height="110">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#eef0f8" stroke-width="3"/>
                            <circle cx="18" cy="18" r="15.9" fill="none"
                                stroke="{{ $approvalRate >= 70 ? '#16a34a' : ($approvalRate >= 40 ? '#d97706' : '#0369a1') }}"
                                stroke-width="3"
                                stroke-dasharray="{{ $approvalRate }}, 100"
                                stroke-linecap="round"/>
                        </svg>
                        <div class="pct" style="color:#0369a1">{{ $approvalRate }}%</div>
                    </div>
                    <div class="rate-sub">{{ $transfersByStatus['approved'] ?? 0 }} approved of decided</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3-Col: Disputes, Transfers, Employment Status ─────────────────── --}}
    <div class="charts-3col fade-up d4">
        
        <div class="chart-card">
            <div class="chart-title">Transfers by Status</div>
            <div class="chart-sub">Period breakdown</div>
            <div style="display:flex;align-items:center;justify-content:center;min-height:180px;">
                <canvas id="transferDoughnut" style="max-width:180px;max-height:180px;"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-title">Employment Status</div>
            <div class="chart-sub">All records, current snapshot</div>
            <canvas id="empStatusBar" height="200"></canvas>
        </div>
    </div>

    {{-- ── Gender + Top Employers ────────────────────────────────────────── --}}
    <div class="charts-eq fade-up d5">
        {{-- Gender --}}
        <div class="chart-card">
            <div class="chart-title">Gender Distribution</div>
            <div class="chart-sub">Registered employees by gender</div>
            <div style="display:flex;align-items:center;justify-content:center;min-height:220px;">
                <canvas id="genderChart" style="max-width:220px;max-height:220px;"></canvas>
            </div>
        </div>
        {{-- Top Employers --}}
        <div class="chart-card">
            <div class="chart-title">Top Employers by Record Count</div>
            <div class="chart-sub">All-time employment records per employer</div>
            <div style="padding-top:4px;">
                @foreach($topEmployers as $i => $record)
                <div class="emp-row">
                    <div class="rank">{{ $i + 1 }}</div>
                    <div class="ename">{{ Str::limit($record->employer?->company_name ?? 'Unknown', 32) }}</div>
                    <div class="ecount">{{ $record->total }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Districts ─────────────────────────────────────────────────────── --}}
    <div class="charts-eq fade-up d6">
        <div class="chart-card">
            <div class="chart-title">Employees by District</div>
            <div class="chart-sub">Top {{ $topDistricts->count() }} districts</div>
            @php $maxDist = $topDistricts->max('total') ?: 1; @endphp
            <div>
                @foreach($topDistricts as $d)
                <div class="dist-row">
                    <div class="dist-name">{{ $d->district ?? 'Unknown' }}</div>
                    <div class="dist-track">
                        <div class="dist-fill" style="width:{{ round(($d->total / $maxDist) * 100) }}%"></div>
                    </div>
                    <div class="dist-count">{{ $d->total }}</div>
                </div>
                @endforeach
                @if($topDistricts->isEmpty())
                <p style="color:var(--muted);font-size:.85rem;text-align:center;padding:20px 0;">No district data</p>
                @endif
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#6b7a9e';

const NAVY    = '#0d1b3e';
const NAVY_LT = '#1e3370';
const GOLD    = '#c9a84c';
const SUCCESS = '#16a34a';
const DANGER  = '#dc2626';
const WARNING = '#d97706';
const INFO    = '#0369a1';
const TEAL    = '#0d9488';

const labels = @json($sharedLabels);

// ── 1. Trend (multi-line) ──────────────────────────────────────────────
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Employees',
                data: @json($employeeCounts),
                borderColor: GOLD, backgroundColor: 'rgba(201,168,76,.08)',
                borderWidth: 2.5, tension: .4, fill: true,
                pointBackgroundColor: GOLD, pointRadius: 4,
            },
            {
                label: 'Employers',
                data: @json($employerCounts),
                borderColor: INFO, backgroundColor: 'rgba(3,105,161,.06)',
                borderWidth: 2.5, tension: .4, fill: true,
                pointBackgroundColor: INFO, pointRadius: 4,
            },
            {
                label: 'Disputes',
                data: @json($disputeCounts),
                borderColor: DANGER, backgroundColor: 'rgba(220,38,38,.05)',
                borderWidth: 2, tension: .4, fill: true,
                pointBackgroundColor: DANGER, pointRadius: 3,
            },
            {
                label: 'Transfers',
                data: @json($transferCounts),
                borderColor: TEAL, backgroundColor: 'rgba(13,148,136,.05)',
                borderWidth: 2, tension: .4, fill: true,
                pointBackgroundColor: TEAL, pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 16 } },
            tooltip: { backgroundColor: NAVY, titleColor: '#fff', bodyColor: 'rgba(255,255,255,.75)', padding: 12, cornerRadius: 8 }
        },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#eef0f8' }, beginAtZero: true }
        }
    }
});

// ── 2. Dispute Doughnut ────────────────────────────────────────────────
const dispData = @json($disputesByStatus);
const dispColors = { pending: WARNING, approved: SUCCESS, rejected: DANGER, resolved: INFO };
new Chart(document.getElementById('disputeDoughnut'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(dispData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            data: Object.values(dispData),
            backgroundColor: Object.keys(dispData).map(k => dispColors[k] ?? '#94a3b8'),
            borderWidth: 3, borderColor: '#fff', hoverOffset: 6
        }]
    },
    options: {
        responsive: true, cutout: '66%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 7, padding: 12, font: { size: 11 } } },
            tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 }
        }
    }
});

// ── 3. Transfer Doughnut ───────────────────────────────────────────────
const trData = @json($transfersByStatus);
const trColors = { pending: WARNING, approved: SUCCESS, rejected: DANGER };
new Chart(document.getElementById('transferDoughnut'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(trData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            data: Object.values(trData),
            backgroundColor: Object.keys(trData).map(k => trColors[k] ?? '#94a3b8'),
            borderWidth: 3, borderColor: '#fff', hoverOffset: 6
        }]
    },
    options: {
        responsive: true, cutout: '66%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 7, padding: 12, font: { size: 11 } } },
            tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 }
        }
    }
});

// ── 4. Employment Status Bar ───────────────────────────────────────────
const empStatusData = @json($employmentByStatus);
const empStatusColors = { Active: SUCCESS, Inactive: '#94a3b8', Terminated: DANGER };
new Chart(document.getElementById('empStatusBar'), {
    type: 'bar',
    data: {
        labels: Object.keys(empStatusData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            label: 'Records',
            data: Object.values(empStatusData),
            backgroundColor: Object.keys(empStatusData).map(k => (empStatusColors[k.charAt(0).toUpperCase() + k.slice(1)] ?? NAVY_LT) + 'cc'),
            borderColor:     Object.keys(empStatusData).map(k =>  empStatusColors[k.charAt(0).toUpperCase() + k.slice(1)] ?? NAVY_LT),
            borderWidth: 2, borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 } },
        scales: { x: { grid: { display: false } }, y: { grid: { color: '#eef0f8' }, beginAtZero: true } }
    }
});

// ── 5. Gender Doughnut ────────────────────────────────────────────────
const genData = @json($genderBreakdown);
const genColors = { male: INFO, female: '#db2777', Male: INFO, Female: '#db2777' };
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(genData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            data: Object.values(genData),
            backgroundColor: Object.keys(genData).map(k => genColors[k] ?? '#94a3b8'),
            borderWidth: 3, borderColor: '#fff', hoverOffset: 6
        }]
    },
    options: {
        responsive: true, cutout: '60%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 14 } },
            tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 }
        }
    }
});

// ── 6. Disputes vs Transfers Bar ──────────────────────────────────────
new Chart(document.getElementById('dvtChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Disputes',
                data: @json($disputeCounts),
                backgroundColor: DANGER + '99', borderColor: DANGER, borderWidth: 1.5, borderRadius: 5,
            },
            {
                label: 'Transfers',
                data: @json($transferCounts),
                backgroundColor: TEAL + '99', borderColor: TEAL, borderWidth: 1.5, borderRadius: 5,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 14 } },
            tooltip: { backgroundColor: NAVY, padding: 10, cornerRadius: 8 }
        },
        scales: { x: { grid: { display: false } }, y: { grid: { color: '#eef0f8' }, beginAtZero: true, stacked: false } }
    }
});
</script>
@endsection