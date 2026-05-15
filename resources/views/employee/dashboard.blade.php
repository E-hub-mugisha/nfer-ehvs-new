@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

{{-- Hero Profile Banner --}}
<div class="profile-banner mb-4">
    <div class="banner-bg"></div>
    <div class="banner-content">

        {{-- Avatar --}}
        <div class="profile-avatar-wrap">
            @if($employee->photo)
            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo" class="profile-avatar-img">
            @else
            <div class="profile-avatar-initials">
                {{ strtoupper(substr($employee->first_name,0,1)) }}{{ strtoupper(substr($employee->last_name,0,1)) }}
            </div>
            @endif
            <div class="avatar-ring"></div>
        </div>

        {{-- Identity --}}
        <div class="profile-identity">
            <div class="profile-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
            <div class="profile-meta-row">
                <span class="meta-pill">
                    <i class="bi bi-fingerprint"></i>
                    NID: {{ $employee->nid }}
                </span>
                <span class="meta-pill">
                    <i class="bi bi-geo-alt"></i>
                    {{ $employee->district }}, {{ $employee->sector }}
                </span>
                <span class="meta-pill {{ $employee->gender === 'Male' ? 'pill-blue' : 'pill-rose' }}">
                    <i class="bi bi-person"></i>
                    {{ $employee->gender }}
                </span>
            </div>
        </div>

        {{-- Quick contact --}}
        <div class="profile-contact ms-auto d-none d-lg-flex">
            <a href="mailto:{{ $employee->email }}" class="contact-chip">
                <i class="bi bi-envelope"></i>
                {{ $employee->email }}
            </a>
            <a href="tel:{{ $employee->phone }}" class="contact-chip">
                <i class="bi bi-telephone"></i>
                {{ $employee->phone }}
            </a>
        </div>

    </div>
</div>

{{-- Stat Cards Row --}}
<div class="row g-3 mb-4">

    {{-- Total Jobs --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card animate-up" style="--d:.05s">
            <div class="stat-icon-wrap" style="--c1:#0e2039;--c2:#162d50">
                <i class="bi bi-briefcase"></i>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalJobs }}</div>
                <div class="stat-label">Total Jobs</div>
            </div>
            <div class="stat-corner-accent"></div>
        </div>
    </div>

    {{-- Active Employment --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card animate-up" style="--d:.1s">
            <div class="stat-icon-wrap" style="--c1:#065f46;--c2:#059669">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $activeEmployment ? 1 : 0 }}</div>
                <div class="stat-label">Active Now</div>
            </div>
            <div class="stat-corner-accent" style="--a-color:rgba(5,150,105,.12)"></div>
        </div>
    </div>

    {{-- Total Years --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card animate-up" style="--d:.15s">
            <div class="stat-icon-wrap" style="--c1:#92400e;--c2:#d4943a">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalYears }}</div>
                <div class="stat-label">Yrs Experience</div>
            </div>
            <div class="stat-corner-accent" style="--a-color:rgba(212,148,58,.12)"></div>
        </div>
    </div>

    {{-- Open Disputes --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card animate-up" style="--d:.2s">
            <div class="stat-icon-wrap" style="--c1:#7c2d12;--c2:#ef4444">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $openDisputes }}</div>
                <div class="stat-label">Open Disputes</div>
            </div>
            <div class="stat-corner-accent" style="--a-color:rgba(239,68,68,.1)"></div>
        </div>
    </div>

</div>

{{-- Main Grid: Timeline + Sidebar --}}
<div class="row g-4">

    {{-- Employment Timeline --}}
    <div class="col-lg-8">
        <div class="dash-card animate-up" style="--d:.25s">

            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="bi bi-clock-history me-2 text-gold"></i>
                    Employment History
                </div>
                <a href="/my-history" class="dash-link">View all <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="dash-card-body p-0">
                @forelse($recentRecords as $index => $record)
                <div class="timeline-item {{ $record->end_date === null ? 'timeline-active' : '' }}">

                    <div class="timeline-connector">
                        <div class="tl-dot {{ $record->end_date === null ? 'tl-dot-active' : '' }}"></div>
                        @if(!$loop->last)<div class="tl-line"></div>@endif
                    </div>

                    <div class="timeline-content">
                        <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                            <div>
                                <div class="tl-company">{{ $record->employer->company_name ?? 'Unknown Employer' }}</div>
                                <div class="tl-position">{{ $record->position }}</div>
                            </div>
                            @if($record->end_date === null)
                            <span class="tl-badge tl-badge-active">Active</span>
                            @else
                            <span class="tl-badge tl-badge-ended">Ended</span>
                            @endif
                        </div>
                        <div class="tl-meta mt-2">
                            <span><i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($record->start_date)->format('M Y') }}
                                &ndash;
                                {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('M Y') : 'Present' }}
                            </span>
                            @if($record->salary)
                            <span><i class="bi bi-cash-stack"></i> {{ number_format($record->salary) }} RWF</span>
                            @endif
                            <span><i class="bi bi-geo-alt"></i> {{ $record->employer->district ?? '—' }}</span>
                        </div>
                    </div>

                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-briefcase"></i>
                    <p>No employment records yet.</p>
                    <small>Your work history will appear here once an employer adds records.</small>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="col-lg-4 d-flex flex-column gap-4">

        {{-- Profile Completeness --}}
        <div class="dash-card animate-up" style="--d:.3s">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="bi bi-person-check me-2 text-gold"></i>
                    Profile
                </div>
                <a href="/my-profile" class="dash-link">Edit</a>
            </div>
            <div class="dash-card-body">

                <div class="profile-detail-row">
                    <span class="pd-label">Date of Birth</span>
                    <span class="pd-value">{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="pd-label">District</span>
                    <span class="pd-value">{{ $employee->district }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="pd-label">Sector</span>
                    <span class="pd-value">{{ $employee->sector }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="pd-label">Phone</span>
                    <span class="pd-value">{{ $employee->phone }}</span>
                </div>
                <div class="profile-detail-row border-0 pb-0">
                    <span class="pd-label">Email</span>
                    <span class="pd-value text-truncate" style="max-width:160px" title="{{ $employee->email }}">{{ $employee->email }}</span>
                </div>

                {{-- Completeness bar --}}
                @php
                $fields = ['nid','first_name','last_name','gender','dob','phone','email','photo','district','sector'];
                $filled = collect($fields)->filter(fn($f) => !empty($employee->$f))->count();
                $pct = intval($filled / count($fields) * 100);
                @endphp
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="pd-label">Profile completeness</span>
                        <span class="pd-value fw-bold text-gold">{{ $pct }}%</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width:{{ $pct }}%"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Recent Disputes --}}
        <div class="dash-card animate-up" style="--d:.35s">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <i class="bi bi-exclamation-circle me-2 text-gold"></i>
                    Recent Disputes
                </div>
                <a href="/my-disputes" class="dash-link">View all <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="dash-card-body p-0">
                @forelse($recentDisputes as $dispute)
                <div class="dispute-row">
                    <div class="dispute-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="dispute-info">
                        <div class="dispute-title">{{ Str::limit($dispute->subject, 38) }}</div>
                        <div class="dispute-date">{{ $dispute->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="dispute-status
                        {{ $dispute->status === 'open'     ? 'ds-open'     : '' }}
                        {{ $dispute->status === 'resolved' ? 'ds-resolved' : '' }}
                        {{ $dispute->status === 'pending'  ? 'ds-pending'  : '' }}
                    ">{{ ucfirst($dispute->status) }}</span>
                </div>
                @empty
                <div class="empty-state py-3">
                    <i class="bi bi-check-circle text-success"></i>
                    <p class="mt-2 mb-0">No disputes filed.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Certificates quick-link --}}
        <div class="cert-promo animate-up" style="--d:.4s">
            <div class="cert-promo-bg"></div>
            <div class="cert-promo-body">
                <i class="bi bi-file-earmark-pdf cert-promo-icon"></i>
                <div>
                    <div class="cert-promo-title">Employment Certificates</div>
                    <div class="cert-promo-sub">Download verified proof of employment</div>
                </div>
                <a href="/certificates" class="cert-promo-btn">
                    <i class="bi bi-download"></i>
                </a>
            </div>
        </div>

    </div>

</div>

{{-- PAGE STYLES --}}
<style>
    :root {
        --navy: #0e2039;
        --navy-l: #162d50;
        --gold: #d4943a;
        --gold-l: #e8ab56;
        --gold-pale: rgba(212, 148, 58, .1);
        --border: rgba(14, 32, 57, .09);
        --text-dim: #64748b;
    }

    /* ── ANIMATIONS ──────────────────────────────── */
    .animate-up {
        animation: fadeUp 0.45s ease both;
        animation-delay: var(--d, 0s);
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── PROFILE BANNER ───────────────────────────── */
    .profile-banner {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(14, 32, 57, .1);
        animation: fadeUp 0.4s ease both;
    }

    .banner-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 55%, #0e2039 100%);
    }

    .banner-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 80% 20%, rgba(212, 148, 58, .18) 0%, transparent 50%),
            repeating-linear-gradient(135deg, rgba(255, 255, 255, .025) 0px, rgba(255, 255, 255, .025) 1px, transparent 1px, transparent 28px);
    }

    .banner-content {
        position: relative;
        display: flex;
        align-items: center;
        gap: 22px;
        padding: 28px 30px;
        flex-wrap: wrap;
    }

    /* Avatar */
    .profile-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .profile-avatar-img,
    .profile-avatar-initials {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        object-fit: cover;
    }

    .profile-avatar-initials {
        background: linear-gradient(135deg, var(--gold), var(--gold-l));
        color: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', sans-serif;
        font-size: 26px;
        font-weight: 700;
    }

    .avatar-ring {
        position: absolute;
        inset: -4px;
        border-radius: 23px;
        border: 2px solid rgba(212, 148, 58, .5);
        pointer-events: none;
    }

    /* Identity */
    .profile-name {
        font-family: 'Sora', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.3px;
        margin-bottom: 8px;
    }

    .profile-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, .1);
        color: rgba(255, 255, 255, .85);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12.5px;
        border: 1px solid rgba(255, 255, 255, .12);
        backdrop-filter: blur(4px);
    }

    .pill-blue {
        background: rgba(37, 99, 235, .25);
        border-color: rgba(37, 99, 235, .3);
    }

    .pill-rose {
        background: rgba(244, 63, 94, .2);
        border-color: rgba(244, 63, 94, .25);
    }

    /* Contact chips */
    .profile-contact {
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .contact-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, .08);
        color: rgba(255, 255, 255, .8);
        padding: 7px 14px;
        border-radius: 10px;
        font-size: 13px;
        border: 1px solid rgba(255, 255, 255, .1);
        text-decoration: none;
        transition: all .2s;
    }

    .contact-chip:hover {
        background: var(--gold-pale);
        border-color: var(--gold);
        color: var(--gold-l);
    }

    /* ── STAT CARDS ───────────────────────────────── */
    .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(14, 32, 57, .05);
        transition: box-shadow .2s, transform .2s;
    }

    .stat-card:hover {
        box-shadow: 0 6px 20px rgba(14, 32, 57, .1);
        transform: translateY(-2px);
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        background: linear-gradient(135deg, var(--c1), var(--c2));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .stat-value {
        font-family: 'Sora', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--navy);
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-dim);
        margin-top: 3px;
        font-weight: 500;
    }

    .stat-corner-accent {
        position: absolute;
        right: -16px;
        bottom: -16px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--a-color, rgba(14, 32, 57, .07));
    }

    /* ── DASH CARD ───────────────────────────────── */
    .dash-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(14, 32, 57, .06);
        overflow: hidden;
    }

    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--border);
    }

    .dash-card-title {
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: var(--navy);
        display: flex;
        align-items: center;
    }

    .dash-link {
        font-size: 12.5px;
        color: var(--gold);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap .2s;
    }

    .dash-link:hover {
        gap: 7px;
    }

    .dash-card-body {
        padding: 18px 22px;
    }

    .text-gold {
        color: var(--gold) !important;
    }

    /* ── TIMELINE ─────────────────────────────────── */
    .timeline-item {
        display: flex;
        gap: 0;
        padding: 0;
        transition: background .2s;
    }

    .timeline-item:hover {
        background: rgba(212, 148, 58, .04);
    }

    .timeline-connector {
        width: 52px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        flex-shrink: 0;
    }

    .tl-dot {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #cbd5e1;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #cbd5e1;
        flex-shrink: 0;
        z-index: 1;
    }

    .tl-dot-active {
        background: var(--gold);
        box-shadow: 0 0 0 3px rgba(212, 148, 58, .3);
    }

    .tl-line {
        width: 2px;
        flex: 1;
        background: linear-gradient(to bottom, #e2e8f0, transparent);
        margin-top: 4px;
        min-height: 20px;
    }

    .timeline-content {
        flex: 1;
        padding: 18px 22px 18px 0;
        border-bottom: 1px solid rgba(14, 32, 57, .05);
    }

    .timeline-item:last-child .timeline-content {
        border-bottom: none;
    }

    .tl-company {
        font-family: 'Sora', sans-serif;
        font-size: 14.5px;
        font-weight: 600;
        color: var(--navy);
    }

    .tl-position {
        font-size: 13px;
        color: var(--text-dim);
        margin-top: 2px;
    }

    .tl-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 12px;
        color: #94a3b8;
    }

    .tl-meta i {
        margin-right: 4px;
    }

    .tl-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .tl-badge-active {
        background: rgba(5, 150, 105, .1);
        color: #059669;
        border: 1px solid rgba(5, 150, 105, .2);
    }

    .tl-badge-ended {
        background: rgba(100, 116, 139, .1);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, .2);
    }

    /* ── PROFILE DETAILS ─────────────────────────── */
    .profile-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 0;
        border-bottom: 1px solid rgba(14, 32, 57, .06);
        font-size: 13.5px;
    }

    .pd-label {
        color: var(--text-dim);
        font-weight: 500;
    }

    .pd-value {
        color: var(--navy);
        font-weight: 600;
    }

    .progress-track {
        height: 6px;
        background: rgba(14, 32, 57, .07);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, var(--gold), var(--gold-l));
        transition: width 1s ease;
    }

    /* ── DISPUTES ─────────────────────────────────── */
    .dispute-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 22px;
        border-bottom: 1px solid rgba(14, 32, 57, .05);
        transition: background .2s;
    }

    .dispute-row:last-child {
        border-bottom: none;
    }

    .dispute-row:hover {
        background: rgba(212, 148, 58, .04);
    }

    .dispute-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(239, 68, 68, .08);
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .dispute-title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--navy);
    }

    .dispute-date {
        font-size: 11.5px;
        color: var(--text-dim);
        margin-top: 2px;
    }

    .dispute-status {
        margin-left: auto;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .ds-open {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, .2);
    }

    .ds-resolved {
        background: rgba(5, 150, 105, .1);
        color: #059669;
        border: 1px solid rgba(5, 150, 105, .2);
    }

    .ds-pending {
        background: rgba(245, 158, 11, .1);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, .2);
    }

    /* ── CERT PROMO CARD ─────────────────────────── */
    .cert-promo {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(212, 148, 58, .35);
        box-shadow: 0 2px 12px rgba(212, 148, 58, .12);
    }

    .cert-promo-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 100%);
    }

    .cert-promo-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 50%, rgba(212, 148, 58, .25) 0%, transparent 65%);
    }

    .cert-promo-body {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
    }

    .cert-promo-icon {
        font-size: 28px;
        color: var(--gold);
        flex-shrink: 0;
    }

    .cert-promo-title {
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }

    .cert-promo-sub {
        font-size: 12px;
        color: rgba(255, 255, 255, .55);
        margin-top: 2px;
    }

    .cert-promo-btn {
        margin-left: auto;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--gold), var(--gold-l));
        color: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        text-decoration: none;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(212, 148, 58, .35);
        transition: transform .2s, box-shadow .2s;
    }

    .cert-promo-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 5px 16px rgba(212, 148, 58, .5);
    }

    /* ── EMPTY STATE ─────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 36px 20px;
        color: #94a3b8;
        font-size: 13px;
    }

    .empty-state i {
        font-size: 32px;
        display: block;
        margin-bottom: 8px;
        opacity: .4;
    }

    .empty-state p {
        color: var(--text-dim);
        font-weight: 500;
        margin: 0 0 4px;
    }
</style>

@endsection