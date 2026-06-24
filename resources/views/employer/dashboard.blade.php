{{-- resources/views/employer/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Employer Dashboard')

@section('content')
<div class="dashboard-wrapper">

    {{-- ── Header ── --}}
    <header class="dash-header">
        <div class="dash-header__inner">
            <div class="dash-header__greeting">
                <p class="dash-header__sub">Welcome back,</p>
                <h1 class="dash-header__name">{{ $employer->company_name }}</h1>
            </div>
            <div class="dash-header__meta">
                <span class="badge badge--{{ $employer->status === 'active' ? 'success' : 'warning' }}">
                    {{ ucfirst($employer->status) }}
                </span>
                <span class="dash-header__date">{{ now()->format('l, d M Y') }}</span>
            </div>
        </div>
    </header>

    {{-- ── Flash messages ── --}}
    @if(session('warning'))
        <div class="alert alert--warning">
            <svg class="alert__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
            {{ session('warning') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert--success">
            <svg class="alert__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Stat cards ── --}}
    <section class="stats-grid">
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5.356-3.765M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a4 4 0 015.356-3.765M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Total Employees</span>
                <span class="stat-card__value">{{ number_format($totalEmployees) }}</span>
            </div>
        </div>

        <div class="stat-card stat-card--green">
            <div class="stat-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Active Employees</span>
                <span class="stat-card__value">{{ number_format($activeEmployees) }}</span>
            </div>
        </div>

        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 002.25 2.25h.75"/></svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Total Records</span>
                <span class="stat-card__value">{{ number_format($totalRecords) }}</span>
            </div>
        </div>

        <div class="stat-card stat-card--amber">
            <div class="stat-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Active Rate</span>
                <span class="stat-card__value">
                    {{ $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100) : 0 }}%
                </span>
            </div>
        </div>
    </section>

    {{-- ── Main grid ── --}}
    <div class="main-grid">

        {{-- Chart --}}
        <section class="card card--chart">
            <div class="card__head">
                <h2 class="card__title">Employment Records — Last 6 Months</h2>
            </div>
            <div class="chart-container">
                <canvas id="monthlyChart" height="120"></canvas>
            </div>
        </section>

        {{-- Company info --}}
        <section class="card card--info">
            <div class="card__head">
                <h2 class="card__title">Company Info</h2>
                <a href="{{ route('employer.profile.edit') }}" class="card__action">Edit</a>
            </div>
            <ul class="info-list">
                <li class="info-list__item">
                    <span class="info-list__label">RDB Number</span>
                    <span class="info-list__value">{{ $employer->rdb_number ?? '—' }}</span>
                </li>
                <li class="info-list__item">
                    <span class="info-list__label">TIN Number</span>
                    <span class="info-list__value">{{ $employer->tin_number ?? '—' }}</span>
                </li>
                <li class="info-list__item">
                    <span class="info-list__label">Email</span>
                    <span class="info-list__value">{{ $employer->email }}</span>
                </li>
                <li class="info-list__item">
                    <span class="info-list__label">Phone</span>
                    <span class="info-list__value">{{ $employer->phone ?? '—' }}</span>
                </li>
                <li class="info-list__item">
                    <span class="info-list__label">Address</span>
                    <span class="info-list__value">{{ $employer->address ?? '—' }}</span>
                </li>
            </ul>
        </section>

        {{-- Recent records --}}
        <section class="card card--table">
            <div class="card__head">
                <h2 class="card__title">Recent Employment Records</h2>
                <a href="{{ route('employer.employees.records.index') }}" class="card__action">View all</a>
            </div>

            @if($recentRecords->isEmpty())
                <div class="empty-state">
                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    <p>No employment records yet.</p>
                    <a href="#" class="btn btn--primary btn--sm">Add First Record</a>
                </div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Position</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRecords as $record)
                            <tr>
                                <td>
                                    <div class="employee-cell">
                                        <span class="employee-cell__avatar">
                                            {{ strtoupper(substr($record->employee->full_name ?? 'U', 0, 1)) }}
                                        </span>
                                        <span class="employee-cell__name">{{ $record->employee->full_name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>{{ $record->job_title ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge badge--{{ $record->employment_status === 'active' ? 'success' : 'neutral' }}">
                                        {{ ucfirst($record->employment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('employer.employees.records.show', $record->employee->id) }}" class="table__link">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>{{-- /.main-grid --}}

</div>{{-- /.dashboard-wrapper --}}

<style>
/* ── Variables ──────────────────────────────────────────── */
:root {
    --c-bg:        #f4f6fb;
    --c-surface:   #ffffff;
    --c-border:    #e4e8f0;
    --c-text:      #1a1d2e;
    --c-muted:     #6b7280;
    --c-blue:      #3b6ef8;
    --c-blue-soft: #eff3fe;
    --c-green:     #16a34a;
    --c-green-soft:#dcfce7;
    --c-purple:    #7c3aed;
    --c-purple-soft:#ede9fe;
    --c-amber:     #d97706;
    --c-amber-soft:#fef3c7;
    --radius:      12px;
    --shadow:      0 1px 3px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
}

/* ── Layout ─────────────────────────────────────────────── */
.dashboard-wrapper { padding: 1.5rem 2rem; max-width: 1280px; margin: 0 auto; }

/* ── Header ─────────────────────────────────────────────── */
.dash-header { margin-bottom: 1.75rem; }
.dash-header__inner { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
.dash-header__sub   { font-size: .8rem; color: var(--c-muted); text-transform: uppercase; letter-spacing: .08em; margin: 0 0 .2rem; }
.dash-header__name  { font-size: 1.6rem; font-weight: 700; color: var(--c-text); margin: 0; }
.dash-header__meta  { display: flex; align-items: center; gap: .75rem; }
.dash-header__date  { font-size: .82rem; color: var(--c-muted); }

/* ── Alerts ─────────────────────────────────────────────── */
.alert { display: flex; align-items: center; gap: .6rem; padding: .75rem 1rem; border-radius: var(--radius); margin-bottom: 1.25rem; font-size: .875rem; }
.alert--warning { background: var(--c-amber-soft); color: var(--c-amber); }
.alert--success { background: var(--c-green-soft); color: var(--c-green); }
.alert__icon    { width: 18px; height: 18px; flex-shrink: 0; }

/* ── Stat cards ─────────────────────────────────────────── */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.stat-card  { background: var(--c-surface); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--c-border); }
.stat-card__icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-card__icon svg { width: 22px; height: 22px; }
.stat-card--blue   .stat-card__icon { background: var(--c-blue-soft);   color: var(--c-blue); }
.stat-card--green  .stat-card__icon { background: var(--c-green-soft);  color: var(--c-green); }
.stat-card--purple .stat-card__icon { background: var(--c-purple-soft); color: var(--c-purple); }
.stat-card--amber  .stat-card__icon { background: var(--c-amber-soft);  color: var(--c-amber); }
.stat-card__body  { display: flex; flex-direction: column; gap: .15rem; }
.stat-card__label { font-size: .75rem; color: var(--c-muted); text-transform: uppercase; letter-spacing: .06em; }
.stat-card__value { font-size: 1.6rem; font-weight: 700; color: var(--c-text); line-height: 1; }

/* ── Main grid ──────────────────────────────────────────── */
.main-grid { display: grid; grid-template-columns: 1fr 320px; grid-template-rows: auto 1fr; gap: 1.25rem; }
.card--chart { grid-column: 1; grid-row: 1; }
.card--info  { grid-column: 2; grid-row: 1 / 3; }
.card--table { grid-column: 1; grid-row: 2; }

/* ── Card ───────────────────────────────────────────────── */
.card { background: var(--c-surface); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--c-border); overflow: hidden; }
.card__head   { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.4rem .9rem; border-bottom: 1px solid var(--c-border); }
.card__title  { font-size: .9rem; font-weight: 600; color: var(--c-text); margin: 0; }
.card__action { font-size: .8rem; color: var(--c-blue); text-decoration: none; font-weight: 500; }
.card__action:hover { text-decoration: underline; }

/* ── Chart ──────────────────────────────────────────────── */
.chart-container { padding: 1.25rem 1.4rem; }

/* ── Info list ──────────────────────────────────────────── */
.info-list { list-style: none; margin: 0; padding: 0; }
.info-list__item  { display: flex; flex-direction: column; gap: .15rem; padding: .85rem 1.4rem; border-bottom: 1px solid var(--c-border); }
.info-list__item:last-child { border-bottom: none; }
.info-list__label { font-size: .72rem; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; }
.info-list__value { font-size: .875rem; color: var(--c-text); font-weight: 500; word-break: break-all; }

/* ── Table ──────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.table th { text-align: left; font-size: .72rem; text-transform: uppercase; letter-spacing: .07em; color: var(--c-muted); padding: .65rem 1.4rem; border-bottom: 1px solid var(--c-border); white-space: nowrap; }
.table td { padding: .85rem 1.4rem; border-bottom: 1px solid var(--c-border); color: var(--c-text); vertical-align: middle; }
.table tr:last-child td { border-bottom: none; }
.table tr:hover td { background: #f9fafb; }
.table__link { color: var(--c-blue); font-size: .8rem; font-weight: 500; text-decoration: none; }
.table__link:hover { text-decoration: underline; }

/* Employee cell */
.employee-cell        { display: flex; align-items: center; gap: .6rem; }
.employee-cell__avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--c-blue-soft); color: var(--c-blue); font-size: .75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.employee-cell__name  { font-weight: 500; }

/* ── Badges ─────────────────────────────────────────────── */
.badge          { display: inline-flex; align-items: center; padding: .2rem .6rem; border-radius: 99px; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.badge--success { background: var(--c-green-soft); color: var(--c-green); }
.badge--warning { background: var(--c-amber-soft); color: var(--c-amber); }
.badge--neutral { background: #f3f4f6; color: #6b7280; }

/* ── Empty state ────────────────────────────────────────── */
.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1.5rem; gap: .75rem; color: var(--c-muted); text-align: center; }
.empty-state__icon { width: 44px; height: 44px; opacity: .45; }
.empty-state p { margin: 0; font-size: .875rem; }

/* ── Buttons ────────────────────────────────────────────── */
.btn         { display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; transition: opacity .15s; }
.btn:hover   { opacity: .88; }
.btn--primary { background: var(--c-blue); color: #fff; }
.btn--sm      { padding: .45rem 1rem; font-size: .8rem; }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 900px) {
    .main-grid { grid-template-columns: 1fr; }
    .card--chart, .card--info, .card--table { grid-column: 1; grid-row: auto; }
    .dashboard-wrapper { padding: 1rem; }
}
@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .dash-header__name { font-size: 1.3rem; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawData = @json($monthlyData);

    const labels = rawData.map(d => d.label);
    const counts = rawData.map(d => d.count);

    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['No data'],
            datasets: [{
                label: 'Records Added',
                data: counts.length ? counts : [0],
                backgroundColor: 'rgba(59, 110, 248, 0.15)',
                borderColor: 'rgba(59, 110, 248, 0.85)',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    titleColor: '#9ca3af',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#6b7280', font: { size: 12 } },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#6b7280', font: { size: 12 }, stepSize: 1 },
                    grid: { color: '#e4e8f0' },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
@endsection