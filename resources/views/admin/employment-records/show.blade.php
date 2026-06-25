@extends('layouts.app')
@section('title', $employmentRecord->employee->first_name . ' ' . $employmentRecord->employee->last_name . ' — Employment Records')

@section('content')

<style>
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
        --font-mono: 'SF Mono', 'Fira Code', ui-monospace, monospace;
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

    /* ── Sidebar ──────────────────────────────────────────────────── */
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

    .sidebar-brand { padding: 28px 24px 20px; border-bottom: 1px solid var(--navy-border); }
    .brand-mark    { display: flex; align-items: center; gap: 10px; }
    .brand-icon    { width: 34px; height: 34px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .brand-icon svg { width: 18px; height: 18px; color: white; }
    .brand-name    { color: white; font-size: 15px; font-weight: 700; letter-spacing: -0.01em; }
    .brand-sub     { color: var(--text-dim); font-size: 11px; font-family: var(--font-mono); letter-spacing: 0.06em; text-transform: uppercase; margin-top: 2px; }

    .sidebar-section       { padding: 20px 12px 8px; }
    .sidebar-section-label { color: var(--text-dim); font-size: 10px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; padding: 0 12px; margin-bottom: 6px; }

    .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 7px; color: #94a3b8; text-decoration: none; font-size: 13.5px; font-weight: 500; transition: background 0.15s, color 0.15s; margin-bottom: 2px; }
    .nav-item:hover  { background: var(--navy-light); color: white; }
    .nav-item.active { background: var(--accent); color: white; }
    .nav-item svg    { width: 16px; height: 16px; flex-shrink: 0; }

    .sidebar-footer { margin-top: auto; padding: 16px 12px; border-top: 1px solid var(--navy-border); }
    .user-card      { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 7px; background: var(--navy-light); }
    .user-avatar    { width: 32px; height: 32px; background: var(--accent-dim); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px; }
    .user-name      { color: white; font-size: 13px; font-weight: 600; }
    .user-role      { color: var(--text-dim); font-size: 11px; }

    /* ── Main ─────────────────────────────────────────────────────── */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }

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

    .topbar-left { display: flex; align-items: center; gap: 12px; }
    .topbar-title { font-size: 16px; font-weight: 700; }

    .breadcrumb { font-size: 12px; color: var(--text-dim); }
    .breadcrumb a { color: var(--accent); text-decoration: none; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border: 1px solid var(--border);
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-mid);
        text-decoration: none;
        transition: all 0.15s;
    }
    .back-btn:hover { background: var(--surface-mid); }
    .back-btn svg   { width: 14px; height: 14px; }

    /* Delete button in topbar */
    .btn-topbar-delete {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border: 1px solid #fca5a5;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        color: #dc2626;
        background: transparent;
        cursor: pointer;
        transition: background .15s, border-color .15s;
    }
    .btn-topbar-delete:hover {
        background: rgba(239,68,68,.07);
        border-color: #dc2626;
    }
    .btn-topbar-delete svg { width: 14px; height: 14px; }

    .content { padding: 28px 32px; flex: 1; }

    /* ── Hero header ──────────────────────────────────────────────── */
    .record-hero {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px 32px;
        display: flex;
        align-items: flex-start;
        gap: 24px;
        margin-bottom: 24px;
    }

    .hero-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), #6366f1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .hero-info   { flex: 1; }
    .hero-name   { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; margin-bottom: 4px; }

    .hero-meta { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 14px; }
    .hero-meta-item { display: flex; align-items: center; gap: 5px; font-size: 12.5px; color: var(--text-mid); }
    .hero-meta-item svg { width: 13px; height: 13px; color: var(--text-dim); }

    .hero-tags { display: flex; gap: 8px; flex-wrap: wrap; }
    .hero-right { text-align: right; }
    .record-id  { font-family: var(--font-mono); font-size: 11px; color: var(--text-dim); margin-bottom: 8px; }

    /* Status badge */
    .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 6px; }
    .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .badge.active     { background: #dcfce7; color: #166534; }
    .badge.active::before { background: var(--green); }
    .badge.inactive   { background: #f1f5f9; color: #475569; }
    .badge.inactive::before { background: #94a3b8; }
    .badge.terminated { background: #fee2e2; color: #991b1b; }
    .badge.terminated::before { background: var(--red); }
    .badge.suspended  { background: #fef9c3; color: #92400e; }
    .badge.suspended::before { background: var(--amber); }

    .duration-pill { display: inline-block; background: var(--surface-mid); color: var(--text-mid); font-size: 11.5px; font-weight: 500; padding: 4px 10px; border-radius: 5px; margin-top: 8px; font-family: var(--font-mono); }

    /* ── Layout grid ──────────────────────────────────────────────── */
    .detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }
    .left-col    { display: flex; flex-direction: column; gap: 20px; }
    .right-col   { display: flex; flex-direction: column; gap: 20px; }

    /* ── Panel ────────────────────────────────────────────────────── */
    .panel { background: white; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .panel-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .panel-title  { font-size: 13px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
    .panel-title svg { width: 15px; height: 15px; color: var(--text-dim); }
    .panel-badge  { font-family: var(--font-mono); font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 4px; background: var(--surface-mid); color: var(--text-mid); }
    .panel-badge.red { background: #fee2e2; color: #991b1b; }
    .panel-body   { padding: 20px; }

    /* ── Field grid ───────────────────────────────────────────────── */
    .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
    .field { padding: 14px 0; border-bottom: 1px solid var(--surface-mid); }
    .field:nth-child(odd)  { padding-right: 20px; }
    .field:nth-child(even) { padding-left: 20px; border-left: 1px solid var(--surface-mid); }
    .field:nth-last-child(-n+2) { border-bottom: none; }
    .field-label { font-size: 10.5px; font-weight: 600; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 4px; }
    .field-value { font-size: 13.5px; color: var(--text); font-weight: 500; }
    .field-value.mono   { font-family: var(--font-mono); font-size: 13px; }
    .field-value.muted  { color: var(--text-dim); font-style: italic; font-weight: 400; }

    .remarks-box { background: var(--surface); border: 1px solid var(--border); border-radius: 7px; padding: 12px 14px; font-size: 13px; color: var(--text-mid); line-height: 1.6; margin-top: 14px; }

    /* ── Timeline ─────────────────────────────────────────────────── */
    .timeline { padding: 4px 0; }
    .timeline-item { display: flex; gap: 14px; padding: 0 20px; position: relative; }
    .timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 33px; top: 28px; bottom: 0; width: 1px; background: var(--border); }
    .timeline-dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 2px var(--border); flex-shrink: 0; margin-top: 14px; position: relative; z-index: 1; }
    .timeline-dot.active     { background: var(--green); box-shadow: 0 0 0 2px var(--green); }
    .timeline-dot.inactive   { background: #94a3b8; }
    .timeline-dot.terminated { background: var(--red); box-shadow: 0 0 0 2px var(--red); }
    .timeline-dot.current    { background: var(--accent); box-shadow: 0 0 0 3px rgba(59,130,246,.3); }
    .timeline-content { flex: 1; padding: 12px 0 20px; border-bottom: 1px solid var(--surface-mid); }
    .timeline-item:last-child .timeline-content { border-bottom: none; }
    .timeline-title { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 2px; }
    .timeline-sub   { font-size: 12px; color: var(--text-mid); margin-bottom: 4px; }
    .timeline-dates { font-size: 11px; color: var(--text-dim); font-family: var(--font-mono); }
    .timeline-current-tag { display: inline-block; font-size: 10px; font-weight: 700; background: #eff6ff; color: var(--accent); padding: 1px 6px; border-radius: 3px; margin-left: 6px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* ── Mini table ───────────────────────────────────────────────── */
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table th { padding: 8px 14px; font-size: 10px; font-weight: 600; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.07em; background: var(--surface); border-bottom: 1px solid var(--border); }
    .mini-table td { padding: 11px 14px; font-size: 12.5px; border-bottom: 1px solid var(--surface-mid); }
    .mini-table tr:last-child td { border-bottom: none; }

    .dispute-status { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
    .dispute-status.open     { background: #fee2e2; color: #991b1b; }
    .dispute-status.resolved { background: #dcfce7; color: #166534; }
    .dispute-status.closed   { background: #f1f5f9; color: #475569; }
    .dispute-status.pending  { background: #fef9c3; color: #92400e; }

    .transfer-status { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
    .transfer-status.pending  { background: #fef9c3; color: #92400e; }
    .transfer-status.approved { background: #dcfce7; color: #166534; }
    .transfer-status.rejected { background: #fee2e2; color: #991b1b; }

    .empty-panel { padding: 28px 20px; text-align: center; color: var(--text-dim); font-size: 13px; }

    /* ── Info list ────────────────────────────────────────────────── */
    .info-list    { list-style: none; }
    .info-list li { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--surface-mid); font-size: 13px; }
    .info-list li:last-child { border-bottom: none; }
    .info-list li svg { width: 14px; height: 14px; color: var(--text-dim); margin-top: 1px; flex-shrink: 0; }
    .info-list-label { color: var(--text-dim); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1px; }
    .info-list-val   { color: var(--text); font-weight: 500; }

    /* ── Delete Modal ─────────────────────────────────────────────── */
    .del-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,28,46,.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .del-overlay.active { display: flex; }

    .del-modal {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 2rem 2rem 1.75rem;
        max-width: 420px;
        width: calc(100% - 2rem);
        box-shadow: 0 24px 64px rgba(15,28,46,.2);
        text-align: center;
        animation: del-in .18s ease;
    }

    @keyframes del-in {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .del-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fee2e2;
        border: 1px solid #fca5a5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #dc2626;
    }
    .del-icon svg { width: 24px; height: 24px; }

    .del-title { font-size: 1.1rem; font-weight: 700; color: var(--text); margin: 0 0 .5rem; }

    .del-body { font-size: .85rem; color: var(--text-mid); line-height: 1.65; margin: 0 0 .75rem; }
    .del-body strong { color: var(--text); font-weight: 600; }

    .del-warning {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 8px;
        padding: .65rem .9rem;
        font-size: .78rem;
        color: #92400e;
        text-align: left;
        margin-bottom: 1.5rem;
        display: flex;
        gap: .5rem;
        align-items: flex-start;
        line-height: 1.55;
    }
    .del-warning svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

    .del-actions { display: flex; gap: .6rem; justify-content: center; }

    .btn-del-confirm {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .55rem 1.3rem;
        font-size: .875rem;
        font-family: var(--font-sans);
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-del-confirm svg { width: 14px; height: 14px; }
    .btn-del-confirm:hover { background: #b91c1c; }

    .btn-del-cancel {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-mid);
        border-radius: 8px;
        padding: .55rem 1.2rem;
        font-size: .875rem;
        font-family: var(--font-sans);
        font-weight: 500;
        cursor: pointer;
        transition: border-color .15s, color .15s;
    }
    .btn-del-cancel:hover { border-color: var(--text-mid); color: var(--text); }

    @media (max-width: 1200px) {
        .detail-grid { grid-template-columns: 1fr; }
        .right-col   { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    }
</style>

<div class="topbar">
    <div class="topbar-left">
        <a href="{{ route('admin.employment-records.index') }}" class="back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Back
        </a>
        <div class="topbar-title">Record Detail</div>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a> /
            <a href="{{ route('admin.employment-records.index') }}">Employment Records</a> /
            #{{ $employmentRecord->id }}
        </div>
    </div>

    {{-- Delete button in topbar right --}}
    <button
        type="button"
        class="btn-topbar-delete"
        onclick="document.getElementById('del-modal').classList.add('active'); document.body.style.overflow='hidden'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        Delete Record
    </button>
</div>

<div class="content">

    @php
        $emp      = $employmentRecord->employee;
        $er       = $employmentRecord->employer;
        $initials = strtoupper(substr(optional($emp)->first_name ?? '?', 0, 1))
                  . strtoupper(substr(optional($emp)->last_name ?? '', 0, 1));
        $start    = $employmentRecord->start_date ? \Carbon\Carbon::parse($employmentRecord->start_date) : null;
        $end      = $employmentRecord->end_date   ? \Carbon\Carbon::parse($employmentRecord->end_date)   : null;
        $duration = $start ? ($end ?? now())->diff($start)->format('%y yrs %m mos') : null;
    @endphp

    {{-- Hero --}}
    <div class="record-hero">
        <div class="hero-avatar">{{ $initials }}</div>

        <div class="hero-info">
            <div class="hero-name">{{ optional($emp)->full_name ?? '—' }}</div>
            <div class="hero-meta">
                @if(optional($emp)->nid)
                <div class="hero-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    NID: <strong>{{ $emp->nid }}</strong>
                </div>
                @endif
                @if(optional($er)->company_name)
                <div class="hero-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    {{ $er->company_name }}
                </div>
                @endif
                @if($employmentRecord->job_title)
                <div class="hero-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                    {{ $employmentRecord->job_title }}
                </div>
                @endif
                @if($employmentRecord->department)
                <div class="hero-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><path d="M6 22v-1a6 6 0 0 1 12 0v1"/></svg>
                    {{ $employmentRecord->department }}
                </div>
                @endif
            </div>
            <div class="hero-tags">
                <span class="badge {{ strtolower($employmentRecord->employment_status ?? 'inactive') }}">
                    {{ ucfirst($employmentRecord->employment_status ?? 'Unknown') }}
                </span>
                @if($duration)
                <span class="duration-pill">{{ $duration }}</span>
                @endif
            </div>
        </div>

        <div class="hero-right">
            <div class="record-id">RECORD #{{ str_pad($employmentRecord->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size:11px;color:var(--text-dim);margin-bottom:4px;">
                Created {{ $employmentRecord->created_at->format('d M Y') }}
            </div>
            @if($employmentRecord->disputes->count() > 0)
            <div style="display:flex;align-items:center;gap:6px;justify-content:flex-end;margin-top:10px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:var(--red)">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span style="font-size:12px;font-weight:600;color:var(--red);">
                    {{ $employmentRecord->disputes->count() }} dispute{{ $employmentRecord->disputes->count() > 1 ? 's' : '' }}
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Two-column detail --}}
    <div class="detail-grid">

        {{-- LEFT --}}
        <div class="left-col">

            {{-- Employment details --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Employment Details
                    </div>
                </div>
                <div class="panel-body">
                    <div class="field-grid">
                        <div class="field">
                            <div class="field-label">Job Title</div>
                            <div class="field-value">{{ $employmentRecord->job_title ?? '—' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Department</div>
                            <div class="field-value">{{ $employmentRecord->department ?? '—' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Start Date</div>
                            <div class="field-value mono">{{ $start ? $start->format('d M Y') : '—' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">End Date</div>
                            <div class="field-value mono">
                                @if($end)
                                    {{ $end->format('d M Y') }}
                                @else
                                    <span style="color:var(--green);font-weight:600;">Present</span>
                                @endif
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-label">Employment Status</div>
                            <div class="field-value">
                                <span class="badge {{ strtolower($employmentRecord->employment_status ?? 'inactive') }}">
                                    {{ ucfirst($employmentRecord->employment_status ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-label">Duration</div>
                            <div class="field-value mono">{{ $duration ?? '—' }}</div>
                        </div>
                        @if($employmentRecord->exit_reason)
                        <div class="field" style="grid-column: 1 / -1;">
                            <div class="field-label">Exit Reason</div>
                            <div class="field-value">{{ $employmentRecord->exit_reason }}</div>
                        </div>
                        @endif
                    </div>
                    @if($employmentRecord->remarks)
                    <div class="field-label" style="margin-top:16px;margin-bottom:6px;">Remarks</div>
                    <div class="remarks-box">{{ $employmentRecord->remarks }}</div>
                    @endif
                </div>
            </div>

            {{-- Disputes --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Disputes
                    </div>
                    @php $dCount = $employmentRecord->disputes->count(); @endphp
                    <span class="panel-badge {{ $dCount > 0 ? 'red' : '' }}">{{ $dCount }}</span>
                </div>
                @if($employmentRecord->disputes->isEmpty())
                <div class="empty-panel">No disputes filed for this record.</div>
                @else
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Filed</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employmentRecord->disputes as $dispute)
                        <tr>
                            <td style="font-family:var(--font-mono);color:var(--text-dim);font-size:11px;">{{ str_pad($dispute->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="max-width:260px;">
                                <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12.5px;">{{ $dispute->description ?? '—' }}</div>
                            </td>
                            <td><span class="dispute-status {{ $dispute->status }}">{{ ucfirst($dispute->status) }}</span></td>
                            <td style="font-family:var(--font-mono);font-size:11px;color:var(--text-dim);">{{ $dispute->created_at->format('d M Y') }}</td>
                            <td><a href="{{ route('admin.disputes.show', $dispute->id) }}" style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:500;">View →</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            {{-- Transfer Requests --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                        Transfer Requests
                    </div>
                    <span class="panel-badge">{{ $transfers->count() }}</span>
                </div>
                @if($transfers->isEmpty())
                <div class="empty-panel">No transfer requests for this employee.</div>
                @else
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Proposed Title</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                        <tr>
                            <td style="font-size:12.5px;">{{ optional($transfer->currentEmployer)->company_name ?? '—' }}</td>
                            <td style="font-size:12.5px;">{{ optional($transfer->requestingEmployer)->company_name ?? '—' }}</td>
                            <td style="font-size:12.5px;">{{ $transfer->proposed_job_title ?? '—' }}</td>
                            <td><span class="transfer-status {{ $transfer->status }}">{{ ucfirst($transfer->status) }}</span></td>
                            <td style="font-family:var(--font-mono);font-size:11px;color:var(--text-dim);">{{ $transfer->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="right-col">

            {{-- Employee info --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Employee
                    </div>
                    @if($emp)
                    <a href="{{ route('admin.employees.show', $emp->id) }}" style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:500;">Profile →</a>
                    @endif
                </div>
                <div class="panel-body" style="padding:0;">
                    <ul class="info-list" style="padding:0 20px;">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <div><div class="info-list-label">Full Name</div><div class="info-list-val">{{ optional($emp)->full_name ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <div><div class="info-list-label">NID</div><div class="info-list-val" style="font-family:var(--font-mono);">{{ optional($emp)->nid ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.9a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.6a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 18z"/></svg>
                            <div><div class="info-list-label">Phone</div><div class="info-list-val">{{ optional($emp)->phone ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <div><div class="info-list-label">Email</div><div class="info-list-val">{{ optional($emp)->email ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div><div class="info-list-label">District / Sector</div><div class="info-list-val">{{ optional($emp)->district ?? '—' }}{{ optional($emp)->sector ? ', ' . $emp->sector : '' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div><div class="info-list-label">Date of Birth</div><div class="info-list-val" style="font-family:var(--font-mono);">{{ optional($emp)->dob ? \Carbon\Carbon::parse($emp->dob)->format('d M Y') : '—' }}</div></div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Employer info --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Employer
                    </div>
                    @if($er)
                    <a href="{{ route('admin.employers.show', $er->id) }}" style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:500;">Profile →</a>
                    @endif
                </div>
                <div class="panel-body" style="padding:0;">
                    <ul class="info-list" style="padding:0 20px;">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            <div><div class="info-list-label">Company Name</div><div class="info-list-val">{{ optional($er)->company_name ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                            <div><div class="info-list-label">RDB Number</div><div class="info-list-val" style="font-family:var(--font-mono);">{{ optional($er)->rdb_number ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                            <div><div class="info-list-label">TIN Number</div><div class="info-list-val" style="font-family:var(--font-mono);">{{ optional($er)->tin_number ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <div><div class="info-list-label">Email</div><div class="info-list-val">{{ optional($er)->email ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div><div class="info-list-label">Address</div><div class="info-list-val">{{ optional($er)->address ?? '—' }}</div></div>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div>
                                <div class="info-list-label">Approval Status</div>
                                <div class="info-list-val">
                                    <span class="badge {{ optional($er)->status === 'approved' ? 'active' : (optional($er)->status === 'rejected' ? 'terminated' : 'suspended') }}">
                                        {{ ucfirst(optional($er)->status ?? 'unknown') }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Employment History --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Employment History
                    </div>
                    <span class="panel-badge">{{ $history->count() }}</span>
                </div>
                @if($history->isEmpty())
                <div class="empty-panel">No history available.</div>
                @else
                <div class="timeline">
                    @foreach($history as $h)
                    @php $isCurrent = $h->id === $employmentRecord->id; @endphp
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $isCurrent ? 'current' : strtolower($h->employment_status ?? 'inactive') }}"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">
                                {{ $h->job_title ?? '—' }}
                                @if($isCurrent)<span class="timeline-current-tag">This record</span>@endif
                            </div>
                            <div class="timeline-sub">{{ optional($h->employer)->company_name ?? '—' }}</div>
                            <div class="timeline-dates">
                                {{ $h->start_date ? \Carbon\Carbon::parse($h->start_date)->format('M Y') : '?' }}
                                —
                                {{ $h->end_date ? \Carbon\Carbon::parse($h->end_date)->format('M Y') : 'Present' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div id="del-modal" class="del-overlay" onclick="closeDelModal(event)">
    <div class="del-modal">
        <div class="del-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <h3 class="del-title">Delete Employment Record</h3>
        <p class="del-body">
            You're about to permanently delete record
            <strong>#{{ str_pad($employmentRecord->id, 5, '0', STR_PAD_LEFT) }}</strong>
            for <strong>{{ optional($emp)->full_name ?? 'this employee' }}</strong>
            at <strong>{{ optional($er)->company_name ?? 'this employer' }}</strong>.
        </p>
        <div class="del-warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            Any linked disputes and transfer requests referencing this record may also be affected. This cannot be undone.
        </div>
        <div class="del-actions">
            <form method="POST" action="{{ route('admin.employment-records.destroy', $employmentRecord) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-del-confirm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    Yes, Delete
                </button>
            </form>
            <button type="button" class="btn-del-cancel" onclick="closeDelModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    function closeDelModal(e) {
        if (e && e.target !== document.getElementById('del-modal')) return;
        document.getElementById('del-modal').classList.remove('active');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDelModal();
    });
</script>

@endsection