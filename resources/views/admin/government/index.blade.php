@extends('layouts.app')
@section('title', 'Government')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --navy: #19265d;
        --navy-90: rgba(25, 38, 93, 0.09);
        --navy-20: rgba(25, 38, 93, 0.20);
        --gold: #C8873A;
        --gold-lt: rgba(200, 135, 58, 0.12);
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

    /* ── PAGE HEADER ────────────────────────── */
    .gov-header {
        background: var(--navy);
        padding: 2.25rem 2rem 2rem;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .gov-header-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 600;
        color: var(--white);
        letter-spacing: .01em;
        line-height: 1.1;
        margin: 0;
    }

    .gov-header-sub {
        color: rgba(255, 255, 255, .55);
        font-size: .825rem;
        margin-top: .3rem;
    }

    .btn-gold {
        background: var(--gold);
        color: var(--white);
        border: none;
        border-radius: 7px;
        padding: .6rem 1.25rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        transition: filter .18s;
        text-decoration: none;
    }

    .btn-gold:hover {
        filter: brightness(1.1);
        color: var(--white);
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
        transition: border-color .18s, background .18s;
        text-decoration: none;
    }

    .btn-outline:hover {
        border-color: var(--navy);
        background: var(--navy-90);
        color: var(--navy);
    }

    /* ── FILTER BAR ─────────────────────────── */
    .filter-bar {
        background: var(--white);
        border-bottom: 1px solid var(--border);
        padding: .85rem 2rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .filter-bar input,
    .filter-bar select {
        border: 1.5px solid var(--border);
        border-radius: 7px;
        padding: .48rem .9rem;
        font-family: 'DM Sans', sans-serif;
        font-size: .83rem;
        color: var(--text);
        background: var(--surface);
        outline: none;
        transition: border-color .18s;
    }

    .filter-bar input:focus,
    .filter-bar select:focus {
        border-color: var(--navy);
    }

    .filter-bar input {
        min-width: 220px;
    }

    .filter-bar .spacer {
        flex: 1;
    }

    .filter-count {
        font-size: .8rem;
        color: var(--muted);
        white-space: nowrap;
    }

    /* ── TABLE CARD ─────────────────────────── */
    .table-card {
        margin: 1.5rem 2rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .gov-table {
        width: 100%;
        border-collapse: collapse;
    }

    .gov-table thead tr {
        background: var(--navy);
    }

    .gov-table thead th {
        color: rgba(255, 255, 255, .75);
        font-size: .75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .07em;
        padding: .75rem 1.1rem;
        text-align: left;
        white-space: nowrap;
        border: none;
    }

    .gov-table thead th:first-child {
        padding-left: 1.5rem;
    }

    .gov-table thead th:last-child {
        padding-right: 1.5rem;
        text-align: right;
    }

    .gov-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .14s;
    }

    .gov-table tbody tr:last-child {
        border-bottom: none;
    }

    .gov-table tbody tr:hover {
        background: var(--navy-90);
    }

    .gov-table tbody td {
        padding: .9rem 1.1rem;
        font-size: .855rem;
        vertical-align: middle;
    }

    .gov-table tbody td:first-child {
        padding-left: 1.5rem;
    }

    .gov-table tbody td:last-child {
        padding-right: 1.5rem;
        text-align: right;
    }

    /* name cell */
    .gov-name {
        font-weight: 600;
        color: var(--text);
    }

    .gov-country {
        font-size: .78rem;
        color: var(--muted);
        margin-top: .1rem;
    }

    /* badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        border-radius: 20px;
        padding: .25rem .7rem;
        font-size: .75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-verified {
        background: var(--success-bg);
        color: var(--success);
    }

    .badge-unverified {
        background: var(--warn-bg);
        color: var(--warn);
    }

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* type tag */
    .type-tag {
        background: var(--navy-90);
        color: var(--navy);
        border-radius: 5px;
        padding: .22rem .6rem;
        font-size: .75rem;
        font-weight: 500;
    }

    /* action buttons in table */
    .action-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .4rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1.5px solid var(--border);
        background: transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        transition: all .16s;
        text-decoration: none;
    }

    .btn-icon:hover {
        border-color: var(--navy);
        color: var(--navy);
        background: var(--navy-90);
    }

    .btn-icon.danger:hover {
        border-color: #c0392b;
        color: #c0392b;
        background: #fdf2f2;
    }

    .btn-icon.verify:hover {
        border-color: var(--success);
        color: var(--success);
        background: var(--success-bg);
    }

    .btn-icon svg {
        width: 14px;
        height: 14px;
    }

    /* empty state */
    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--muted);
    }

    .empty-state svg {
        width: 48px;
        height: 48px;
        opacity: .35;
        margin-bottom: 1rem;
    }

    .empty-state p {
        margin: 0;
        font-size: .9rem;
    }

    /* pagination */
    .pagination-wrap {
        padding: .85rem 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    /* ── MODAL ──────────────────────────────── */
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
        max-width: 580px;
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
        color: var(--white);
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
        line-height: 1;
        transition: background .16s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, .22);
        color: #fff;
    }

    .modal-body {
        padding: 1.5rem 1.75rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row.full {
        grid-template-columns: 1fr;
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

    .form-group input,
    .form-group select,
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
        transition: border-color .18s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--navy);
        background: #fff;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .modal-foot {
        padding: 1rem 1.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .6rem;
    }

    /* ── VERIFY MODAL ───────────────────────── */
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

    .alert {
        border-radius: 8px;
        padding: .75rem 1rem;
        font-size: .83rem;
        margin-bottom: 1rem;
    }

    .alert-success {
        background: var(--success-bg);
        color: var(--success);
        border: 1px solid rgba(26, 138, 90, .2);
    }

    .alert-error {
        background: #fdf2f2;
        color: #c0392b;
        border: 1px solid rgba(192, 57, 43, .2);
    }

    @media (max-width: 768px) {
        .gov-header {
            padding: 1.5rem 1rem;
        }

        .filter-bar {
            padding: .75rem 1rem;
        }

        .table-card {
            margin: 1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .gov-table thead th:nth-child(3),
        .gov-table tbody td:nth-child(3),
        .gov-table thead th:nth-child(4),
        .gov-table tbody td:nth-child(4) {
            display: none;
        }
    }
</style>


{{-- ── FLASH MESSAGES ──────────────────────────────────── --}}
@if(session('success'))
<div class="alert alert-success" style="margin: 1rem 2rem 0; border-radius: 8px;">
    ✓ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-error" style="margin: 1rem 2rem 0; border-radius: 8px;">
    {{ session('error') }}
</div>
@endif

{{-- ── PAGE HEADER ─────────────────────────────────────── --}}
<div class="gov-header">
    <div>
        <h1 class="gov-header-title">Government Registry</h1>
        <p class="gov-header-sub">{{ $governments->total() }} registered {{ Str::plural('entity', $governments->total()) }} · {{ $governments->where('is_verified', true)->count() ?? '' }} verified</p>
    </div>

    <button class="btn-gold" onclick="openModal('createModal')">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Register Government
    </button>

</div>

{{-- ── FILTER BAR ──────────────────────────────────────── --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.government.users.index') }}" style="display:contents;">
        <input type="text" name="search" placeholder="Search name or country…" value="{{ request('search') }}">
        <select name="type">
            <option value="">All types</option>
            @foreach(['Republic','Monarchy','Federal','Parliamentary','Presidential','Theocracy','Other'] as $t)
            <option value="{{ $t }}" @selected(request('type')===$t)>{{ $t }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All statuses</option>
            <option value="verified" @selected(request('status')==='verified' )>Verified</option>
            <option value="unverified" @selected(request('status')==='unverified' )>Unverified</option>
        </select>
        <button type="submit" class="btn-gold" style="padding:.48rem 1rem; font-size:.82rem;">Apply</button>
        @if(request()->hasAny(['search','type','status']))
        <a href="{{ route('admin.government.users.index') }}" class="btn-outline" style="font-size:.82rem;">Clear</a>
        @endif
    </form>
    <span class="spacer"></span>
    <span class="filter-count">Showing {{ $governments->firstItem() }}–{{ $governments->lastItem() }} of {{ $governments->total() }}</span>
</div>

{{-- ── TABLE CARD ──────────────────────────────────────── --}}
<div class="table-card">
    <table class="gov-table">
        <thead>
            <tr>
                <th>Government</th>
                <th>Type</th>
                <th>Est. Year</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($governments as $gov)
            <tr>
                {{-- Name + Country --}}
                <td>
                    <div class="gov-name">{{ $gov->name }}</div>
                    <div class="gov-country">{{ $gov->country }}</div>
                </td>
                {{-- Type --}}
                <td><span class="type-tag">{{ $gov->government_type ?? '—' }}</span></td>
                {{-- Established --}}
                <td>{{ $gov->established_year ?? '—' }}</td>
                {{-- Contact --}}
                <td>
                    @if($gov->contact_email)
                    <a href="mailto:{{ $gov->contact_email }}" style="color:var(--navy); font-size:.8rem; text-decoration:none;">{{ $gov->contact_email }}</a>
                    @else
                    <span style="color:var(--muted)">—</span>
                    @endif
                </td>
                {{-- Status badge --}}
                <td>
                    @if($gov->is_verified)
                    <span class="badge badge-verified">
                        <span class="badge-dot"></span> Verified
                    </span>
                    @else
                    <span class="badge badge-unverified">
                        <span class="badge-dot"></span> Pending
                    </span>
                    @endif
                </td>
                {{-- Actions --}}
                <td>
                    <div class="action-row">
                        {{-- View --}}
                        <a href="{{ route('admin.government.users.show', $gov) }}" class="btn-icon" title="View details">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </a>

                        {{-- Verify / Unverify --}}
                        @if(!$gov->is_verified)
                        <button class="btn-icon verify" title="Verify government"
                            onclick="openVerifyModal({{ $gov->id }}, '{{ addslashes($gov->name) }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>
                        @else
                        <form method="POST" action="{{ route('admin.government.users.unverify', $gov) }}" style="display:inline;"
                            onsubmit="return confirm('Remove verification from {{ addslashes($gov->name) }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-icon danger" title="Revoke verification">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </form>
                        @endif
                        {{-- Edit --}}
                        <button class="btn-icon" title="Edit government"
                            onclick='openEditModal(@json($gov))'>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.government.users.destroy', $gov) }}" style="display:inline;"
                            onsubmit="return confirm('Permanently delete {{ addslashes($gov->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                        <p>No governments found. Adjust your filters or register a new one.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($governments->hasPages())
    <div class="pagination-wrap">
        {{ $governments->withQueryString()->links() }}
    </div>
    @endif
</div>


{{-- ══════════════════════════════════════════════════════
     CREATE MODAL
══════════════════════════════════════════════════════ --}}

<div class="modal-backdrop" id="createModal" onclick="closeOnBackdrop(event,'createModal')">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <p class="modal-head-title">Register Government</p>
                <p class="modal-head-sub">Add a new government entity to the registry</p>
            </div>
            <button class="modal-close" onclick="closeModal('createModal')" type="button">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.government.users.store') }}">
            @csrf
            <div class="modal-body">

                @if($errors->any())
                <div class="alert alert-error" style="margin-bottom:1rem;">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Government Name <span style="color:var(--gold)">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Republic of Rwanda" required>
                    </div>
                    <div class="form-group">
                        <label>Country <span style="color:var(--gold)">*</span></label>
                        <input type="text" name="country" value="{{ old('country') }}" placeholder="e.g. Rwanda" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Government Type <span style="color:var(--gold)">*</span></label>
                        <select name="government_type" required>
                            <option value="">— Select type —</option>
                            @foreach(['Ministry','Department','Agency','Authority'] as $t)
                            <option value="{{ $t }}" @selected(old('government_type')===$t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Established Year</label>
                        <input type="number" name="established_year" value="{{ old('established_year') }}"
                            placeholder="{{ date('Y') }}" min="1" max="{{ date('Y') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="contact@gov.rw">
                    </div>
                    <div class="form-group">
                        <label>Official Website</label>
                        <input type="url" name="website" value="{{ old('website') }}" placeholder="https://gov.rw">
                    </div>
                </div>

                <!-- rdbnumber and tin number -->
                <div class="form-row">
                    <div class="form-group">
                        <label>RDB Number</label>
                        <input type="text" name="rdb_number" value="{{ old('rdb_number') }}" placeholder="RDB-XXXX-XXXX">
                    </div>
                    <div class="form-group">
                        <label>TIN Number</label>
                        <input type="text" name="tin_number" value="{{ old('tin_number') }}" placeholder="TIN-XXXX-XXXX">
                    </div>
                </div>

            </div>
            <div class="modal-foot">
                <button type="button" class="btn-outline" onclick="closeModal('createModal')">Cancel</button>
                <button type="submit" class="btn-gold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Register Government
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="editModal" onclick="closeOnBackdrop(event,'editModal')">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <p class="modal-head-title">Edit Government</p>
                <p class="modal-head-sub">Update entity details</p>
            </div>
            <button class="modal-close" onclick="closeModal('editModal')" type="button">✕</button>
        </div>

        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Government Name <span style="color:var(--gold)">*</span></label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>Country <span style="color:var(--gold)">*</span></label>
                        <input type="text" name="country" id="edit_country" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Government Type <span style="color:var(--gold)">*</span></label>
                        <select name="government_type" id="edit_government_type" required>
                            @foreach(['Ministry','Department','Agency','Authority'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Established Year</label>
                        <input type="number" name="established_year" id="edit_established_year" min="1800" max="{{ date('Y') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Email</label>
                        <input type="email" name="contact_email" id="edit_contact_email">
                    </div>
                    <div class="form-group">
                        <label>Official Website</label>
                        <input type="url" name="website" id="edit_website">
                    </div>
                </div>

                <!-- RDB Number and TIN Number -->
                <div class="form-row">
                    <div class="form-group">
                        <label>RDB Number</label>
                        <input type="text" name="rdb_number" value="{{ old('rdb_number') }}" placeholder="RDB-XXXX-XXXX">
                    </div>
                    <div class="form-group">
                        <label>TIN Number</label>
                        <input type="text" name="tin_number" value="{{ old('tin_number') }}" placeholder="TIN-XXXX-XXXX">
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-outline" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-gold">Save Changes</button>
            </div>
        </form>
    </div>
</div>
{{-- ══════════════════════════════════════════════════════
     VERIFY MODAL
══════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="verifyModal" onclick="closeOnBackdrop(event,'verifyModal')">
    <div class="modal-box" style="max-width: 460px;">
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
                    <label>Verification Notes <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0">(optional)</span></label>
                    <textarea name="verification_notes" placeholder="Any notes about the verification process, documents checked, etc."></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-outline" onclick="closeModal('verifyModal')">Cancel</button>
                <button type="submit" class="btn-gold" style="background: var(--success);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Confirm Verification
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
        document.getElementById('verifyForm').action = `/admin/government/users/${govId}/verify`;
        openModal('verifyModal');
    }
    // Re-open create modal on validation errors
    @if($errors -> any())
    document.addEventListener('DOMContentLoaded', () => openModal('createModal'));
    @endif

    function openEditModal(gov) {
    document.getElementById('editForm').action = `/admin/government/users/${gov.id}`;
    document.getElementById('edit_name').value = gov.name ?? '';
    document.getElementById('edit_country').value = gov.country ?? '';
    document.getElementById('edit_government_type').value = gov.government_type ?? '';
    document.getElementById('edit_established_year').value = gov.established_year ?? '';
    document.getElementById('edit_contact_email').value = gov.contact_email ?? '';
    document.getElementById('edit_website').value = gov.website ?? '';
    openModal('editModal');
}
</script>


@endsection