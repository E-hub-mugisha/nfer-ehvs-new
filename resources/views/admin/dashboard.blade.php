@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
    /* ── Reset & Base ─────────────────────────────────────────── */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --navy: #0f1c2e;
        --navy-mid: #162337;
        --navy-light: #1e3050;
        --navy-border: #253d5c;
        --accent: #3b82f6;
        --accent-dim: #1d4ed8;
        --surface: #f8fafc;
        --surface-mid: #f1f5f9;
        --border: #e2e8f0;
        --text: #0f172a;
        --text-mid: #475569;
        --text-dim: #94a3b8;
        --green: #10b981;
        --red: #ef4444;
        --amber: #f59e0b;
        --blue: #3b82f6;
        --font-mono: 'SF Mono', 'Fira Code', 'Cascadia Code', ui-monospace, monospace;
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        --sidebar-w: 260px;
    }

    body {
        font-family: var(--font-sans);
        background: var(--surface);
        color: var(--text);
        display: flex;
        min-height: 100vh;
        font-size: 14px;
        line-height: 1.5;
    }

    /* ── Sidebar ──────────────────────────────────────────────── */
    .sidebar {
        width: var(--sidebar-w);
        background: var(--navy);
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        z-index: 100;
    }

    .sidebar-brand {
        padding: 28px 24px 20px;
        border-bottom: 1px solid var(--navy-border);
    }

    .sidebar-brand .brand-mark {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-icon {
        width: 34px;
        height: 34px;
        background: var(--accent);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-icon svg {
        width: 18px;
        height: 18px;
        color: white;
    }

    .brand-name {
        color: white;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .brand-sub {
        color: var(--text-dim);
        font-size: 11px;
        font-family: var(--font-mono);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .sidebar-section {
        padding: 20px 12px 8px;
    }

    .sidebar-section-label {
        color: var(--text-dim);
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0 12px;
        margin-bottom: 6px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 7px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: background 0.15s, color 0.15s;
        margin-bottom: 2px;
    }

    .nav-item:hover {
        background: var(--navy-light);
        color: white;
    }

    .nav-item.active {
        background: var(--accent);
        color: white;
    }

    .nav-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .nav-badge {
        margin-left: auto;
        background: var(--red);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        font-family: var(--font-mono);
    }

    .nav-badge.amber {
        background: var(--amber);
        color: var(--navy);
    }

    .nav-badge.blue {
        background: var(--accent);
    }

    .sidebar-footer {
        margin-top: auto;
        padding: 16px 12px;
        border-top: 1px solid var(--navy-border);
    }

    .user-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 7px;
        background: var(--navy-light);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: var(--accent-dim);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 12px;
    }

    .user-name {
        color: white;
        font-size: 13px;
        font-weight: 600;
    }

    .user-role {
        color: var(--text-dim);
        font-size: 11px;
    }

    /* ── Main Layout ──────────────────────────────────────────── */
    .main {
        margin-left: var(--sidebar-w);
        flex: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ── Topbar ───────────────────────────────────────────────── */
    .topbar {
        background: white;
        border-bottom: 1px solid var(--border);
        padding: 0 32px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .topbar-date {
        font-size: 12px;
        color: var(--text-mid);
        font-family: var(--font-mono);
    }

    .topbar-btn {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid var(--border);
        background: white;
        color: var(--text-mid);
        text-decoration: none;
        transition: background 0.15s;
    }

    .topbar-btn:hover {
        background: var(--surface-mid);
    }

    /* ── Content ──────────────────────────────────────────────── */
    .content {
        padding: 28px 32px;
        flex: 1;
    }

    /* ── Pending actions banner ───────────────────────────────── */
    .actions-banner {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
    }

    .action-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border-radius: 8px;
        background: white;
        border: 1px solid var(--border);
        text-decoration: none;
        color: var(--text);
        flex: 1;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .action-pill:hover {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .action-pill-count {
        font-family: var(--font-mono);
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
    }

    .action-pill-count.red {
        color: var(--red);
    }

    .action-pill-count.amber {
        color: var(--amber);
    }

    .action-pill-count.blue {
        color: var(--accent);
    }

    .action-pill-label {
        font-size: 12.5px;
        color: var(--text-mid);
        line-height: 1.3;
    }

    .action-pill-arrow {
        margin-left: auto;
        color: var(--text-dim);
    }

    /* ── Stat grid ────────────────────────────────────────────── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 20px 22px;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-mid);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 10px;
    }

    .stat-value {
        font-family: var(--font-mono);
        font-size: 32px;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .stat-sub {
        margin-top: 8px;
        font-size: 11.5px;
        color: var(--text-dim);
    }

    .stat-sub span {
        font-family: var(--font-mono);
        font-weight: 600;
    }

    .stat-sub span.green {
        color: var(--green);
    }

    .stat-sub span.red {
        color: var(--red);
    }

    .stat-sub span.amber {
        color: var(--amber);
    }

    /* ── Two-column lower layout ──────────────────────────────── */
    .lower-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px;
    }

    .chart-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    /* ── Panel ────────────────────────────────────────────────── */
    .panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text);
    }

    .panel-link {
        font-size: 12px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
    }

    .panel-body {
        padding: 20px;
    }

    /* ── Charts ───────────────────────────────────────────────── */
    .chart-wrap {
        position: relative;
        height: 180px;
    }

    /* ── Activity feed ────────────────────────────────────────── */
    .feed-item {
        display: flex;
        gap: 12px;
        padding: 13px 20px;
        border-bottom: 1px solid var(--surface-mid);
    }

    .feed-item:last-child {
        border-bottom: none;
    }

    .feed-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .feed-dot.blue {
        background: #eff6ff;
    }

    .feed-dot.blue svg {
        color: var(--blue);
    }

    .feed-dot.green {
        background: #f0fdf4;
    }

    .feed-dot.green svg {
        color: var(--green);
    }

    .feed-dot.red {
        background: #fef2f2;
    }

    .feed-dot.red svg {
        color: var(--red);
    }

    .feed-dot.yellow {
        background: #fffbeb;
    }

    .feed-dot.yellow svg {
        color: var(--amber);
    }

    .feed-dot svg {
        width: 14px;
        height: 14px;
    }

    .feed-content {
        flex: 1;
        min-width: 0;
    }

    .feed-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .feed-sublabel {
        font-size: 12px;
        color: var(--text-mid);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .feed-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 2px;
    }

    .feed-time {
        font-size: 11px;
        color: var(--text-dim);
        font-family: var(--font-mono);
    }

    .badge {
        font-size: 10px;
        font-weight: 600;
        padding: 1px 7px;
        border-radius: 4px;
        text-transform: capitalize;
    }

    .badge.pending {
        background: #fef9c3;
        color: #92400e;
    }

    .badge.approved {
        background: #dcfce7;
        color: #166534;
    }

    .badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .badge.rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge.open {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge.resolved {
        background: #f0f9ff;
        color: #075985;
    }

    .badge.closed {
        background: #f1f5f9;
        color: #475569;
    }

    /* ── Donut stats ──────────────────────────────────────────── */
    .donut-legend {
        margin-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .legend-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-label {
        font-size: 12px;
        color: var(--text-mid);
        flex: 1;
        text-transform: capitalize;
    }

    .legend-count {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
    }

    /* ── Empty state ──────────────────────────────────────────── */
    .empty-feed {
        padding: 40px 20px;
        text-align: center;
        color: var(--text-dim);
        font-size: 13px;
    }

    /* ── Responsive ───────────────────────────────────────────── */
    @media (max-width: 1200px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .lower-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .actions-banner {
            flex-direction: column;
        }

        .chart-row {
            grid-template-columns: 1fr;
        }

        .sidebar {
            transform: translateX(-100%);
        }

        .main {
            margin-left: 0;
        }
    }
</style>
</head>


<div class="content">

    {{-- ── Pending actions banner ─────────────────────────────── --}}
    @if(collect($pendingActions)->sum('count') > 0)
    <div class="actions-banner">
        @foreach($pendingActions as $action)
        @if($action['count'] > 0)
        <a href="{{ route($action['route']) }}" class="action-pill">
            <div class="action-pill-count {{ $action['colour'] }}">{{ $action['count'] }}</div>
            <div class="action-pill-label">{{ $action['label'] }}</div>
            <div class="action-pill-arrow">→</div>
        </a>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ── Stats grid ─────────────────────────────────────────── --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-sub">
                <span class="green">{{ number_format($stats['total_employees']) }}</span> employees ·
                <span>{{ number_format($stats['total_employers']) }}</span> employers
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Employment Records</div>
            <div class="stat-value">{{ number_format($stats['total_records']) }}</div>
            <div class="stat-sub">
                <span class="green">{{ number_format($stats['active_records']) }}</span> active ·
                <span class="red">{{ number_format($stats['inactive_records']) }}</span> inactive
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Transfer Requests</div>
            <div class="stat-value">{{ number_format($stats['total_transfers']) }}</div>
            <div class="stat-sub">
                <span class="amber">{{ number_format($stats['pending_transfers']) }}</span> pending ·
                <span class="green">{{ number_format($stats['approved_transfers']) }}</span> approved
            </div>
        </div>
    </div>

    {{-- ── Lower grid ─────────────────────────────────────────── --}}
    <div class="lower-grid">

        {{-- Left: Charts + table --}}
        <div>
            <div class="chart-row">
                {{-- Employer status chart --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Employer Approvals</div>
                    </div>
                    <div class="panel-body">
                        <div class="chart-wrap">
                            <canvas id="employerChart"></canvas>
                        </div>
                        <div class="donut-legend">
                            @php
                            $eColours = ['approved' => '#10b981', 'pending' => '#f59e0b', 'rejected' => '#ef4444'];
                            @endphp
                            @foreach($employersByStatus as $status => $count)
                            <div class="legend-row">
                                <div class="legend-dot" style="background:{{ $eColours[$status] ?? '#94a3b8' }}"></div>
                                <div class="legend-label">{{ $status }}</div>
                                <div class="legend-count">{{ $count }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Dispute status chart --}}
                
            </div>

            {{-- Monthly registrations bar chart --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">New Registrations — Last 6 Months</div>
                </div>
                <div class="panel-body">
                    <div class="chart-wrap" style="height: 200px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Activity feed --}}
        <div class="panel" style="max-height: 680px; display:flex; flex-direction:column;">
            <div class="panel-header">
                <div class="panel-title">Recent Activity</div>
                <span style="font-size:11px;color:var(--text-dim);font-family:var(--font-mono);">LIVE</span>
            </div>
            <div style="overflow-y:auto; flex:1;">
                @forelse($activityFeed as $event)
                <div class="feed-item">
                    <div class="feed-dot {{ $event['colour'] }}">
                        @if($event['icon'] === 'building')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="15" rx="1" />
                            <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                        </svg>
                        @elseif($event['icon'] === 'alert')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        @elseif($event['icon'] === 'transfer')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="17 1 21 5 17 9" />
                            <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                            <polyline points="7 23 3 19 7 15" />
                            <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                        </svg>
                        @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        @endif
                    </div>
                    <div class="feed-content">
                        <div class="feed-label">{{ $event['label'] }}</div>
                        <div class="feed-sublabel">{{ $event['sublabel'] }}</div>
                        <div class="feed-meta">
                            <span class="feed-time">
                                {{ \Carbon\Carbon::parse($event['created_at'])->diffForHumans() }}
                            </span>
                            @if($event['badge'])
                            <span class="badge {{ $event['badge'] }}">{{ $event['badge'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-feed">No recent activity.</div>
                @endforelse
            </div>
        </div>

    </div>{{-- /lower-grid --}}
</div>{{-- /content --}}

<script>
    // ── Chart.js defaults ───────────────────────────────────────────────
    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#94a3b8';

    // Shared donut options
    const donutOpts = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                }
            }
        }
    };

    // ── Employer donut ──────────────────────────────────────────────────
    const employerData = @json($employersByStatus);
    new Chart(document.getElementById('employerChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(employerData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(employerData),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: donutOpts
    });

    // ── Dispute donut ───────────────────────────────────────────────────
    const disputeData = @json($disputesByStatus);
    new Chart(document.getElementById('disputeChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(disputeData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(disputeData),
                backgroundColor: ['#ef4444', '#10b981', '#94a3b8', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: donutOpts
    });

    // ── Monthly bar chart ───────────────────────────────────────────────
    const empData = @json($monthlyEmployees);
    const emplData = @json($monthlyEmployers);

    // Merge and sort all months
    const allMonths = [...new Set([...Object.keys(empData), ...Object.keys(emplData)])].sort();

    const fmtMonth = m => {
        const [y, mo] = m.split('-');
        return new Date(y, mo - 1).toLocaleString('default', {
            month: 'short',
            year: '2-digit'
        });
    };

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: allMonths.map(fmtMonth),
            datasets: [{
                    label: 'Employees',
                    data: allMonths.map(m => empData[m] ?? 0),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    borderSkipped: false,
                },
                {
                    label: 'Employers',
                    data: allMonths.map(m => emplData[m] ?? 0),
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        padding: 16,
                        usePointStyle: true,
                        pointStyleWidth: 8
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    },
                    grid: {
                        color: '#f1f5f9'
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
</script>

@endsection