{{-- resources/views/employer/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Company Profile')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
<style>
    /* ── Design tokens ───────────────────────────────────────── */
    :root {
        --c-bg: #f0f2f8;
        --c-surface: #ffffff;
        --c-surface-2: #f8f9fc;
        --c-border: #e2e6f0;
        --c-border-focus: #3b6ef8;
        --c-text: #111827;
        --c-muted: #6b7280;
        --c-accent: #3b6ef8;
        --c-accent-dark: #2451d6;
        --c-accent-soft: #eff3fe;
        --c-success: #16a34a;
        --c-success-soft: #dcfce7;
        --c-danger: #dc2626;
        --c-danger-soft: #fee2e2;
        --c-warning: #d97706;
        --c-warning-soft: #fef3c7;
        --radius-sm: 8px;
        --radius: 14px;
        --radius-lg: 20px;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, .06);
        --shadow: 0 2px 8px rgba(0, 0, 0, .07), 0 0 0 1px rgba(0, 0, 0, .04);
        --shadow-focus: 0 0 0 3px rgba(59, 110, 248, .18);
        --font-display: 'DM Serif Display', Georgia, serif;
        --font-body: 'DM Sans', system-ui, sans-serif;
        --transition: 160ms ease;
    }

    /* ── Reset / base ────────────────────────────────────────── */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    body {
        font-family: var(--font-body);
        background: var(--c-bg);
        color: var(--c-text);
    }

    /* ── Page shell ──────────────────────────────────────────── */
    .profile-page {
        min-height: 100vh;
        padding: 2.5rem 1.5rem 4rem;
    }

    /* ── Page header ─────────────────────────────────────────── */
    .page-header {
        max-width: 860px;
        margin: 0 auto 2.25rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header__left {}

    .page-header__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--c-accent);
        margin-bottom: .45rem;
    }

    .page-header__eyebrow svg {
        width: 13px;
        height: 13px;
    }

    .page-header__title {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 400;
        color: var(--c-text);
        margin: 0;
        line-height: 1.15;
    }

    .page-header__sub {
        margin: .35rem 0 0;
        font-size: .875rem;
        color: var(--c-muted);
    }

    .page-header__back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .82rem;
        font-weight: 500;
        color: var(--c-muted);
        text-decoration: none;
        padding: .45rem .85rem;
        border: 1px solid var(--c-border);
        border-radius: var(--radius-sm);
        background: var(--c-surface);
        transition: color var(--transition), border-color var(--transition);
        margin-top: .25rem;
    }

    .page-header__back:hover {
        color: var(--c-accent);
        border-color: var(--c-accent);
    }

    .page-header__back svg {
        width: 15px;
        height: 15px;
    }

    /* ── Alert ───────────────────────────────────────────────── */
    .alert {
        max-width: 860px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .85rem 1.1rem;
        border-radius: var(--radius-sm);
        font-size: .875rem;
        line-height: 1.5;
        animation: slideDown .25s ease;
    }

    .alert svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: .05rem;
    }

    .alert--success {
        background: var(--c-success-soft);
        color: var(--c-success);
        border: 1px solid #bbf7d0;
    }

    .alert--error {
        background: var(--c-danger-soft);
        color: var(--c-danger);
        border: 1px solid #fecaca;
    }

    .alert--warning {
        background: var(--c-warning-soft);
        color: var(--c-warning);
        border: 1px solid #fde68a;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Form card ───────────────────────────────────────────── */
    .profile-card {
        max-width: 860px;
        margin: 0 auto;
        background: var(--c-surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid var(--c-border);
        overflow: hidden;
        animation: fadeUp .3s ease;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Section blocks ──────────────────────────────────────── */
    .form-section {
        padding: 1.75rem 2rem;
        border-bottom: 1px solid var(--c-border);
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section__header {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.4rem;
    }

    .form-section__icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: var(--c-accent-soft);
        color: var(--c-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .form-section__icon svg {
        width: 18px;
        height: 18px;
    }

    .form-section__title {
        font-size: .95rem;
        font-weight: 600;
        color: var(--c-text);
        margin: 0;
    }

    .form-section__desc {
        font-size: .78rem;
        color: var(--c-muted);
        margin: .1rem 0 0;
    }

    /* ── Form grid ───────────────────────────────────────────── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem 1.4rem;
    }

    .form-grid--full {
        grid-template-columns: 1fr;
    }

    .col-span-2 {
        grid-column: span 2;
    }

    /* ── Field ───────────────────────────────────────────────── */
    .field {
        display: flex;
        flex-direction: column;
        gap: .4rem;
    }

    .field__label {
        font-size: .78rem;
        font-weight: 600;
        color: var(--c-text);
        letter-spacing: .015em;
    }

    .field__label .required {
        color: var(--c-danger);
        margin-left: .2rem;
    }

    .field__hint {
        font-size: .72rem;
        color: var(--c-muted);
        margin-top: -.15rem;
    }

    .field__input,
    .field__textarea {
        width: 100%;
        padding: .65rem .9rem;
        background: var(--c-surface-2);
        border: 1.5px solid var(--c-border);
        border-radius: var(--radius-sm);
        font-family: var(--font-body);
        font-size: .875rem;
        color: var(--c-text);
        outline: none;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        appearance: none;
    }

    .field__input:hover,
    .field__textarea:hover {
        border-color: #b8c3e0;
        background: #fff;
    }

    .field__input:focus,
    .field__textarea:focus {
        border-color: var(--c-border-focus);
        background: #fff;
        box-shadow: var(--shadow-focus);
    }

    .field__input.is-error,
    .field__textarea.is-error {
        border-color: var(--c-danger);
        background: #fff5f5;
    }

    .field__input.is-error:focus,
    .field__textarea.is-error:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .14);
    }

    .field__textarea {
        resize: vertical;
        min-height: 90px;
        line-height: 1.55;
    }

    .field__error {
        font-size: .75rem;
        color: var(--c-danger);
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .field__error svg {
        width: 13px;
        height: 13px;
        flex-shrink: 0;
    }

    /* ── Status badge inside card ────────────────────────────── */
    .status-row {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .85rem 1.1rem;
        background: var(--c-surface-2);
        border: 1px solid var(--c-border);
        border-radius: var(--radius-sm);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-dot--active {
        background: var(--c-success);
        box-shadow: 0 0 0 3px var(--c-success-soft);
    }

    .status-dot--pending {
        background: var(--c-warning);
        box-shadow: 0 0 0 3px var(--c-warning-soft);
    }

    .status-dot--inactive {
        background: var(--c-muted);
        box-shadow: 0 0 0 3px #f3f4f6;
    }

    .status-label {
        font-size: .82rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-label--active {
        color: var(--c-success);
    }

    .status-label--pending {
        color: var(--c-warning);
    }

    .status-label--inactive {
        color: var(--c-muted);
    }

    .status-note {
        font-size: .78rem;
        color: var(--c-muted);
        margin-left: auto;
    }

    /* ── Form footer ─────────────────────────────────────────── */
    .form-footer {
        padding: 1.25rem 2rem;
        background: var(--c-surface-2);
        border-top: 1px solid var(--c-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .form-footer__note {
        font-size: .78rem;
        color: var(--c-muted);
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .form-footer__note svg {
        width: 14px;
        height: 14px;
    }

    .form-footer__actions {
        display: flex;
        align-items: center;
        gap: .65rem;
    }

    /* ── Buttons ─────────────────────────────────────────────── */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .65rem 1.35rem;
        border-radius: var(--radius-sm);
        font-family: var(--font-body);
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: background var(--transition), opacity var(--transition), transform var(--transition);
        white-space: nowrap;
    }

    .btn:active {
        transform: scale(.98);
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }

    .btn--primary {
        background: var(--c-accent);
        color: #fff;
    }

    .btn--primary:hover {
        background: var(--c-accent-dark);
    }

    .btn--ghost {
        background: transparent;
        color: var(--c-muted);
        border: 1.5px solid var(--c-border);
    }

    .btn--ghost:hover {
        color: var(--c-text);
        border-color: #b8c3e0;
        background: #f3f5fb;
    }

    /* Loading state */
    .btn--loading {
        opacity: .7;
        pointer-events: none;
    }

    .btn--loading .btn-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, .35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .65s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ── Responsive ──────────────────────────────────────────── */
    @media (max-width: 640px) {
        .profile-page {
            padding: 1.25rem 1rem 3rem;
        }

        .form-section {
            padding: 1.25rem 1.1rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .col-span-2 {
            grid-column: span 1;
        }

        .form-footer {
            padding: 1rem 1.1rem;
        }

        .page-header__title {
            font-size: 1.55rem;
        }
    }
</style>

<div class="profile-page">

    {{-- ── Page header ── --}}
    <header class="page-header">
        <div class="page-header__left">
            <p class="page-header__eyebrow">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd" />
                </svg>
                Company Profile
            </p>
            <h1 class="page-header__title">Edit Your Profile</h1>
            <p class="page-header__sub">Keep your company details accurate and up to date.</p>
        </div>
        <a href="{{ route('employer.dashboard') }}" class="page-header__back">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
    </header>

    {{-- ── Flash alerts ── --}}
    @if(session('success'))
    <div class="alert alert--success" role="alert">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert--warning" role="alert">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('warning') }}</span>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert--error" role="alert">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
        </svg>
        <span>Please fix the errors below before saving.</span>
    </div>
    @endif

    {{-- ── Form card ── --}}
    <form
        method="POST"
        action="{{ route('employer.profile.update') }}"
        id="profileForm"
        novalidate>
        @csrf
        @method('PATCH')

        <div class="profile-card">

            {{-- ① Company Identity ── --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div>
                        <p class="form-section__title">Company Identity</p>
                        <p class="form-section__desc">Legal name and registration numbers.</p>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Company name --}}
                    <div class="field col-span-2">
                        <label class="field__label" for="company_name">
                            Company Name <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            class="field__input {{ $errors->has('company_name') ? 'is-error' : '' }}"
                            value="{{ old('company_name', $employer->company_name) }}"
                            placeholder="e.g. Acme Corporation Ltd"
                            required
                            autocomplete="organization">
                        @error('company_name')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    {{-- RDB number --}}
                    <div class="field">
                        <label class="field__label" for="rdb_number">RDB Number</label>
                        <p class="field__hint">Rwanda Development Board registration number.</p>
                        <input
                            type="text"
                            id="rdb_number"
                            name="rdb_number"
                            class="field__input {{ $errors->has('rdb_number') ? 'is-error' : '' }}"
                            value="{{ old('rdb_number', $employer->rdb_number) }}"
                            placeholder="e.g. 100012345">
                        @error('rdb_number')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    {{-- TIN number --}}
                    <div class="field">
                        <label class="field__label" for="tin_number">TIN Number</label>
                        <p class="field__hint">Tax Identification Number issued by RRA.</p>
                        <input
                            type="text"
                            id="tin_number"
                            name="tin_number"
                            class="field__input {{ $errors->has('tin_number') ? 'is-error' : '' }}"
                            value="{{ old('tin_number', $employer->tin_number) }}"
                            placeholder="e.g. 102345678">
                        @error('tin_number')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ② Contact Details ── --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="form-section__title">Contact Details</p>
                        <p class="form-section__desc">How employees and administrators can reach you.</p>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Email --}}
                    <div class="field">
                        <label class="field__label" for="email">
                            Business Email <span class="required">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="field__input {{ $errors->has('email') ? 'is-error' : '' }}"
                            value="{{ old('email', $employer->email) }}"
                            placeholder="hr@company.rw"
                            required
                            autocomplete="email">
                        @error('email')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="field">
                        <label class="field__label" for="phone">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="field__input {{ $errors->has('phone') ? 'is-error' : '' }}"
                            value="{{ old('phone', $employer->phone) }}"
                            placeholder="+250 7XX XXX XXX"
                            autocomplete="tel">
                        @error('phone')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="field col-span-2">
                        <label class="field__label" for="address">Physical Address</label>
                        <textarea
                            id="address"
                            name="address"
                            class="field__textarea {{ $errors->has('address') ? 'is-error' : '' }}"
                            placeholder="Street, District, Province, Rwanda"
                            rows="3">{{ old('address', $employer->address) }}</textarea>
                        @error('address')
                        <span class="field__error">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ③ Account Status ── --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <p class="form-section__title">Account Status</p>
                        <p class="form-section__desc">Managed by administrators — contact support to change.</p>
                    </div>
                </div>

                <div class="status-row">
                    <span class="status-dot status-dot--{{ $employer->status }}"></span>
                    <span class="status-label status-label--{{ $employer->status }}">{{ ucfirst($employer->status) }}</span>
                    @if($employer->status === 'pending')
                    <span class="status-note">Your profile is under review.</span>
                    @elseif($employer->status === 'active')
                    <span class="status-note">Your account is fully active.</span>
                    @else
                    <span class="status-note">Contact support for assistance.</span>
                    @endif
                </div>
            </div>

            {{-- ── Footer / actions ── --}}
            <div class="form-footer">
                <p class="form-footer__note">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                    </svg>
                    Changes are saved securely.
                </p>
                <div class="form-footer__actions">
                    <a href="{{ route('employer.dashboard') }}" class="btn btn--ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn--primary" id="submitBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

        </div>{{-- /.profile-card --}}
    </form>

</div>{{-- /.profile-page --}}

<script>
    (function() {
        // ── Loading state on submit ──────────────────────────────
        const form = document.getElementById('profileForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            submitBtn.classList.add('btn--loading');
            submitBtn.innerHTML = '<span class="btn-spinner"></span> Saving…';
            submitBtn.disabled = true;
        });

        // ── Unsaved-changes warning ──────────────────────────────
        let isDirty = false;
        const inputs = form.querySelectorAll('input, textarea');

        inputs.forEach(el => {
            el.addEventListener('input', () => {
                isDirty = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Clear dirty flag on intentional submit
        form.addEventListener('submit', () => {
            isDirty = false;
        });
    })();
</script>
@endsection