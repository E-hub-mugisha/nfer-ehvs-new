@extends('layouts.app')
@section('title', 'Government Details')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --navy: #19265d;
        --navy-90: rgba(25, 38, 93, 0.09);
        --gold: #C8873A;
        --gold-lt: rgba(200, 135, 58, 0.14);
        --white: #ffffff;
        --surface: #f7f8fc;
        --border: #e4e7f0;
        --text: #1a1f36;
        --muted: #6b7394;
        --success: #1a8a5a;
        --success-bg: #edf7f2;
        --warn-bg: #fff8ef;
        --warn: #b85c00;
        --radius: 10px;
        --shadow: 0 2px 16px rgba(25, 38, 93, .08);
    }

    body {
        background: var(--surface);
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
    }

    /* ── BACK BAR ───────────────────────────── */
    .back-bar {
        padding: .8rem 2rem;
        background: var(--white);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .back-link {
        color: var(--muted);
        text-decoration: none;
        font-size: .82rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: color .16s;
    }

    .back-link:hover {
        color: var(--navy);
    }

    .back-bar-sep {
        color: var(--border);
    }

    .back-bar-current {
        font-size: .82rem;
        color: var(--text);
        font-weight: 500;
    }

    /* ── HERO CARD ──────────────────────────── */
    .hero-card {
        background: var(--navy);
        margin: 1.5rem 2rem 0;
        border-radius: var(--radius);
        padding: 2rem 2.25rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        right: -40px;
        top: -60px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(200, 135, 58, .08);
        pointer-events: none;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        right: 60px;
        bottom: -80px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(200, 135, 58, .05);
        pointer-events: none;
    }

    .hero-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: var(--gold-lt);
        border: 1.5px solid rgba(200, 135, 58, .3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        flex-shrink: 0;
        margin-bottom: .85rem;
    }

    .hero-icon svg {
        width: 26px;
        height: 26px;
    }

    .hero-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--white);
        margin: 0 0 .2rem;
        line-height: 1.15;
    }

    .hero-country {
        color: rgba(255, 255, 255, .5);
        font-size: .88rem;
        margin-bottom: .75rem;
    }

    .hero-meta {
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-wrap: wrap;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        border-radius: 20px;
        padding: .3rem .8rem;
        font-size: .75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-verified {
        background: rgba(26, 138, 90, .2);
        color: #6ee9b5;
    }

    .badge-unverified {
        background: rgba(184, 92, 0, .2);
        color: #ffc875;
    }

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .type-pill {
        background: rgba(255, 255, 255, .1);
        color: rgba(255, 255, 255, .75);
        border-radius: 20px;
        padding: .28rem .75rem;
        font-size: .75rem;
    }

    /* hero actions */
    .hero-actions {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .btn-gold {
        background: var(--gold);
        color: var(--white);
        border: none;
        border-radius: 7px;
        padding: .6rem 1.2rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .83rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        transition: filter .18s;
    }

    .btn-gold:hover {
        filter: brightness(1.1);
        color: var(--white);
    }

    .btn-gold.green {
        background: var(--success);
    }

    .btn-outline-white {
        background: rgba(255, 255, 255, .1);
        color: rgba(255, 255, 255, .85);
        border: 1.5px solid rgba(255, 255, 255, .2);
        border-radius: 7px;
        padding: .55rem 1.1rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .83rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        transition: background .18s;
    }

    .btn-outline-white:hover {
        background: rgba(255, 255, 255, .18);
        color: #fff;
    }

    .btn-danger-outline {
        background: transparent;
        color: #e57373;
        border: 1.5px solid rgba(229, 115, 115, .35);
        border-radius: 7px;
        padding: .55rem 1.1rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .83rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        transition: background .18s;
    }

    .btn-danger-outline:hover {
        background: rgba(229, 115, 115, .12);
    }

    /* ── CONTENT GRID ───────────────────────── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.25rem;
        margin: 1.25rem 2rem 2rem;
    }

    /* ── SECTION CARD ───────────────────────── */
    .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .section-head {
        padding: .9rem 1.4rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-title {
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--navy);
    }

    .section-body {
        padding: 1.25rem 1.4rem;
    }

    /* details grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem;
    }

    .detail-item label {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--muted);
        margin-bottom: .28rem;
    }

    .detail-item .val {
        font-size: .9rem;
        color: var(--text);
        font-weight: 500;
        word-break: break-all;
    }

    .detail-item .val a {
        color: var(--navy);
        text-decoration: none;
    }

    .detail-item .val a:hover {
        text-decoration: underline;
    }

    .detail-item .val.empty {
        color: var(--muted);
        font-weight: 400;
        font-style: italic;
    }

    .detail-full {
        grid-column: 1 / -1;
    }

    /* verification panel */
    .verify-panel {
        border-radius: 8px;
        padding: 1rem 1.1rem;
        margin-bottom: .75rem;
    }

    .verify-panel.verified {
        background: var(--success-bg);
        border: 1px solid rgba(26, 138, 90, .2);
    }

    .verify-panel.unverified {
        background: var(--warn-bg);
        border: 1px solid rgba(184, 92, 0, .2);
    }

    .verify-panel-title {
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: .3rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .verify-panel.verified .verify-panel-title {
        color: var(--success);
    }

    .verify-panel.unverified .verify-panel-title {
        color: var(--warn);
    }

    .verify-panel p {
        font-size: .8rem;
        color: var(--muted);
        margin: 0;
    }

    /* timeline */
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .timeline li {
        display: flex;
        gap: .85rem;
        padding-bottom: 1rem;
        position: relative;
    }

    .timeline li:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 22px;
        width: 1px;
        bottom: 0;
        background: var(--border);
    }

    .tl-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--navy-90);
        border: 2px solid var(--border);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tl-dot svg {
        width: 10px;
        height: 10px;
        color: var(--navy);
    }

    .tl-body {
        flex: 1;
    }

    .tl-event {
        font-size: .82rem;
        font-weight: 500;
        color: var(--text);
    }

    .tl-time {
        font-size: .75rem;
        color: var(--muted);
        margin-top: .1rem;
    }

    @media (max-width: 900px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .hero-card,
        .content-grid {
            margin-left: 1rem;
            margin-right: 1rem;
        }

        .back-bar {
            padding: .7rem 1rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


{{-- ── BREADCRUMB ──────────────────────────────────────── --}}
<div class="back-bar">
    <a href="{{ route('admin.government.users.index') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Governments
    </a>
    <span class="back-bar-sep">/</span>
    <span class="back-bar-current">{{ $government->name }}</span>
</div>

{{-- ── HERO ─────────────────────────────────────────────── --}}
<div class="hero-card">
    <div>
        <div class="hero-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
        </div>
        <h1 class="hero-name">{{ $government->name }}</h1>
        <p class="hero-country">{{ $government->country }}@if($government->established_year) · Est. {{ $government->established_year }}@endif</p>
        <div class="hero-meta">
            @if($government->is_verified)
            <span class="badge badge-verified"><span class="badge-dot"></span> Verified</span>
            @else
            <span class="badge badge-unverified"><span class="badge-dot"></span> Pending Verification</span>
            @endif
            @if($government->government_type)
            <span class="type-pill">{{ $government->government_type }}</span>
            @endif
        </div>
    </div>
    <div class="hero-actions">
        @can('admin')
        @if(!$government->is_verified)
        <button class="btn-gold green"
            onclick="openVerifyModal({{ $government->id }}, '{{ addslashes($government->name) }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            Verify
        </button>
        @else
        <form method="POST" action="{{ route('admin.government.users.unverify', $government) }}"
            onsubmit="return confirm('Remove verification from {{ addslashes($government->name) }}?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-outline-white">Revoke Verification</button>
        </form>
        @endif
        <a href="{{ route('admin.government.users.edit', $government) }}" class="btn-outline-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('admin.government.users.destroy', $government) }}"
            onsubmit="return confirm('Permanently delete {{ addslashes($government->name) }}? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger-outline">Delete</button>
        </form>
        @endcan
    </div>
</div>

{{-- ── CONTENT GRID ─────────────────────────────────────── --}}
<div class="content-grid">

    {{-- LEFT COLUMN --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Details --}}
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">Government Details</span>
            </div>
            <div class="section-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Full Name</label>
                        <div class="val">{{ $government->name }}</div>
                    </div>
                    <div class="detail-item">
                        <label>Country</label>
                        <div class="val">{{ $government->country }}</div>
                    </div>
                    <div class="detail-item">
                        <label>Government Type</label>
                        <div class="val {{ !$government->government_type ? 'empty' : '' }}">
                            {{ $government->government_type ?? 'Not specified' }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Established Year</label>
                        <div class="val {{ !$government->established_year ? 'empty' : '' }}">
                            {{ $government->established_year ?? 'Not specified' }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Contact Email</label>
                        <div class="val {{ !$government->contact_email ? 'empty' : '' }}">
                            @if($government->contact_email)
                            <a href="mailto:{{ $government->contact_email }}">{{ $government->contact_email }}</a>
                            @else
                            Not provided
                            @endif
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Official Website</label>
                        <div class="val {{ !$government->website ? 'empty' : '' }}">
                            @if($government->website)
                            <a href="{{ $government->website }}" target="_blank" rel="noopener">
                                {{ parse_url($government->website, PHP_URL_HOST) ?? $government->website }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-left:2px">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    <polyline points="15 3 21 3 21 9" />
                                    <line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </a>
                            @else
                            Not provided
                            @endif
                        </div>
                    </div>
                    <div class="detail-item detail-full">
                        <label>Registered By</label>
                        <div class="val">
                            {{ $government->user?->name ?? 'Unknown' }}
                            <span style="color:var(--muted); font-weight:400; font-size:.8rem"> · {{ $government->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- RIGHT COLUMN --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Verification Status --}}
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">Verification Status</span>
            </div>
            <div class="section-body">
                @if($government->is_verified)
                <div class="verify-panel verified">
                    <div class="verify-panel-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Verified Government
                    </div>
                    <p>Verified on {{ $government->verified_at?->format('M j, Y \a\t H:i') }}</p>
                </div>
                @if($government->verifiedBy)
                <div class="detail-item" style="margin-bottom:.85rem;">
                    <label>Verified By</label>
                    <div class="val">{{ $government->verifiedBy->name }}</div>
                </div>
                @endif
                @if($government->verification_notes)
                <div class="detail-item">
                    <label>Verification Notes</label>
                    <div class="val" style="font-size:.82rem; color:var(--muted); font-weight:400; line-height:1.55;">{{ $government->verification_notes }}</div>
                </div>
                @endif
                @else
                <div class="verify-panel unverified">
                    <div class="verify-panel-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Pending Verification
                    </div>
                    <p>This government has not yet been reviewed by an administrator.</p>
                </div>
                <button class="btn-gold green" style="width:100%; justify-content:center; margin-top:.25rem;"
                    onclick="openVerifyModal({{ $government->id }}, '{{ addslashes($government->name) }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Verify Now
                </button>
                <!-- unverify button -->

                @endif
                <button class="btn-danger-outline"
                    style="width:100%; justify-content:center; margin-top:.6rem;"
                    onclick="openUnverifyModal({{ $government->id }}, '{{ addslashes($government->name) }}')">
                    Revoke Verification
                </button>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">Activity</span>
            </div>
            <div class="section-body">
                <ul class="timeline">
                    @if($government->is_verified && $government->verified_at)
                    <li>
                        <div class="tl-dot" style="background:var(--success-bg); border-color:rgba(26,138,90,.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="tl-body">
                            <div class="tl-event">Government verified</div>
                            <div class="tl-time">{{ $government->verified_at->format('M j, Y · H:i') }}</div>
                        </div>
                    </li>
                    @endif
                    <li>
                        <div class="tl-dot">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </div>
                        <div class="tl-body">
                            <div class="tl-event">Government registered</div>
                            <div class="tl-time">{{ $government->created_at->format('M j, Y · H:i') }}</div>
                        </div>
                    </li>
                    @if($government->updated_at->ne($government->created_at))
                    <li>
                        <div class="tl-dot">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </div>
                        <div class="tl-body">
                            <div class="tl-event">Last updated</div>
                            <div class="tl-time">{{ $government->updated_at->format('M j, Y · H:i') }}</div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

    </div>{{-- /right --}}
</div>{{-- /content-grid --}}


<style>
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(10, 14, 40, .5);
        backdrop-filter: blur(2px);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-backdrop.open {
        display: flex;
    }

    .modal-box {
        background: var(--white);
        border-radius: 14px;
        box-shadow: 0 24px 64px rgba(10, 14, 40, .25);
        width: 100%;
        max-width: 460px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slide-up .2s ease;
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-head {
        background: var(--navy);
        padding: 1.4rem 1.75rem;
        border-radius: 14px 14px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-head-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }

    .modal-head-sub {
        color: rgba(255, 255, 255, .5);
        font-size: .78rem;
        margin-top: .2rem;
    }

    .modal-close {
        background: rgba(255, 255, 255, .12);
        border: none;
        border-radius: 6px;
        width: 30px;
        height: 30px;
        color: rgba(255, 255, 255, .7);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, .22);
        color: #fff;
    }

    .verify-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--success-bg);
        color: var(--success);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .verify-icon svg {
        width: 28px;
        height: 28px;
    }

    .verify-modal-body {
        text-align: center;
        padding: 1.75rem 1.75rem .5rem;
    }

    .verify-modal-body h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 .4rem;
    }

    .verify-modal-body p {
        color: var(--muted);
        font-size: .85rem;
        margin: 0 0 1.25rem;
    }

    .verify-modal-notes {
        padding: 0 1.75rem;
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: .78rem;
        font-weight: 500;
        color: var(--navy);
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: .38rem;
    }

    .form-group textarea {
        width: 100%;
        border: 1.5px solid var(--border);
        border-radius: 7px;
        padding: .55rem .85rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .85rem;
        color: var(--text);
        background: var(--surface);
        outline: none;
        box-sizing: border-box;
        resize: vertical;
        min-height: 80px;
        transition: border-color .18s;
    }

    .form-group textarea:focus {
        border-color: var(--navy);
        background: #fff;
    }

    .modal-foot {
        padding: 1rem 1.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .6rem;
    }

    .btn-outline {
        background: transparent;
        color: var(--navy);
        border: 1.5px solid var(--border);
        border-radius: 7px;
        padding: .55rem 1.1rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .82rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        transition: border-color .18s, background .18s;
    }

    .btn-outline:hover {
        border-color: var(--navy);
        background: var(--navy-90);
    }
</style>

<div class="modal-backdrop" id="verifyModal" onclick="closeOnBackdrop(event,'verifyModal')">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <p class="modal-head-title">Verify Government</p>
                <p class="modal-head-sub">Confirm entity legitimacy</p>
            </div>
            <button class="modal-close" onclick="closeModal('verifyModal')" type="button">✕</button>
        </div>
        <form method="POST" id="verifyForm" action="">
            @csrf @method('PATCH')
            <div class="verify-modal-body">
                <div class="verify-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
                <h3 id="verifyGovName">—</h3>
                <p>You are about to mark this government as <strong>verified</strong>. This action will be logged with your account and timestamp.</p>
            </div>
            <div class="verify-modal-notes">
                <div class="form-group">
                    <label>Verification Notes <span style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0">(optional)</span></label>
                    <textarea name="verification_notes" placeholder="Documents checked, verification method, any relevant notes…"></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-outline" onclick="closeModal('verifyModal')">Cancel</button>
                <button type="submit" class="btn-gold" style="background:var(--success);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Confirm Verification
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="unverifyModal" onclick="closeOnBackdrop(event,'unverifyModal')">
    <div class="modal-box">

        <div class="modal-head">
            <div>
                <p class="modal-head-title">Revoke Verification</p>
                <p class="modal-head-sub">This will mark the government as unverified</p>
            </div>
            <button class="modal-close" onclick="closeModal('unverifyModal')" type="button">✕</button>
        </div>

        <form method="POST" id="unverifyForm" action="">
            @csrf
            @method('PATCH')

            <div class="verify-modal-body">
                <div class="verify-icon" style="background: var(--warn-bg); color: var(--warn);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>

                <h3 id="unverifyGovName">—</h3>

                <p>
                    You are about to remove verification from this government.
                    This action will be logged.
                </p>
            </div>

            <div class="verify-modal-notes">
                <div class="form-group">
                    <label>Reason (optional)</label>
                    <textarea name="verification_notes"
                        placeholder="Reason for removing verification..."></textarea>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-outline" onclick="closeModal('unverifyModal')">
                    Cancel
                </button>

                <button type="submit" class="btn-danger-outline">
                    Confirm Revoke
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }

    function closeOnBackdrop(e, id) {
        if (e.target.id === id) closeModal(id);
    }

    function openVerifyModal(govId, govName) {
        document.getElementById('verifyGovName').textContent = govName;

        let url = "{{ route('admin.government.users.verify', ':id') }}";
        url = url.replace(':id', govId);

        document.getElementById('verifyForm').action = url;

        openModal('verifyModal');
    }

    function openUnverifyModal(govId, govName) {
        document.getElementById('unverifyGovName').textContent = govName;

        let url = "{{ route('admin.government.users.unverify', ':id') }}";
        url = url.replace(':id', govId);

        document.getElementById('unverifyForm').action = url;

        openModal('unverifyModal');
    }
</script>


@endsection