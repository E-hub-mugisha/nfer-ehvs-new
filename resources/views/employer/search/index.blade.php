@extends('layouts.app')
@section('title', 'Search Employee')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Lora:ital@0;1&display=swap');

    .se-page {
        font-family: 'DM Sans', sans-serif;
        min-height: 100vh;
        background: #F7F5F2;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 3.5rem 1rem;
    }

    .se-inner {
        width: 100%;
        max-width: 560px;
    }

    /* ── Header ── */
    .se-eyebrow {
        font-size: 11px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #9E9B95;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .se-heading {
        font-family: 'Lora', Georgia, serif;
        font-size: 28px;
        font-weight: 400;
        font-style: italic;
        color: #1C1A17;
        margin: 0 0 10px;
        line-height: 1.2;
    }

    .se-sub {
        font-size: 14px;
        color: #6B6862;
        line-height: 1.65;
        margin: 0 0 2rem;
        max-width: 420px;
    }

    /* ── Alerts ── */
    .se-alert {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .se-alert-warning {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        color: #92400E;
    }

    .se-alert-danger {
        background: #FFF5F5;
        border: 1px solid #FCA5A5;
        color: #991B1B;
    }

    .se-alert i {
        font-size: 15px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Card ── */
    .se-card {
        background: #FFFFFF;
        border: 1px solid #E8E5E0;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
    }

    /* ── Label ── */
    .se-label {
        font-size: 11.5px;
        font-weight: 500;
        color: #9E9B95;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
    }

    /* ── Input group ── */
    .se-input-row {
        display: flex;
        border: 1.5px solid #E2DDD6;
        border-radius: 10px;
        overflow: hidden;
        transition: border-color 0.15s;
        background: #fff;
    }

    .se-input-row:focus-within {
        border-color: #1C1A17;
    }

    .se-input-row.is-invalid {
        border-color: #EF4444;
    }

    .se-icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        background: #F7F5F2;
        border-right: 1px solid #E8E5E0;
        flex-shrink: 0;
    }

    .se-icon-box i {
        font-size: 17px;
        color: #A09D97;
    }

    .se-input {
        flex: 1;
        height: 50px;
        padding: 0 14px;
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        color: #1C1A17;
        background: transparent;
        border: none;
        outline: none;
    }

    .se-input::placeholder {
        color: #B8B4AE;
    }

    .se-btn {
        height: 50px;
        padding: 0 22px;
        background: #1C1A17;
        color: #FFFFFF;
        border: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .se-btn:hover {
        background: #3A3731;
    }

    .se-btn:active {
        background: #111;
    }

    .se-btn i {
        font-size: 15px;
    }

    /* ── Hint pills ── */
    .se-hints {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 12px;
    }

    .se-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #7A776F;
        background: #F7F5F2;
        border: 1px solid #E8E5E0;
        border-radius: 20px;
        padding: 4px 11px;
    }

    .se-pill i {
        font-size: 12px;
    }

    /* ── Divider ── */
    .se-divider {
        border: none;
        border-top: 1px solid #F0EDE8;
        margin: 1.4rem 0;
    }

    /* ── Steps ── */
    .se-steps {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .se-step {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: #F7F5F2;
        border-radius: 10px;
    }

    .se-step-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 1px solid #DDD9D2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 500;
        color: #7A776F;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .se-step-text {
        font-size: 12.5px;
        color: #7A776F;
        line-height: 1.5;
    }

    .se-step-text strong {
        color: #1C1A17;
        font-weight: 500;
        display: block;
        margin-bottom: 1px;
    }

    @media (max-width: 480px) {
        .se-heading { font-size: 24px; }
        .se-steps { grid-template-columns: 1fr; }
        .se-btn { padding: 0 14px; }
    }
</style>

<div class="se-page">
    <div class="se-inner">

        {{-- Header --}}
        <p class="se-eyebrow">Employee Lookup</p>
        <h1 class="se-heading">Find an employee</h1>
        <p class="se-sub">
            Search by National ID (NID) or full name.
            If the employee isn't registered yet, you'll be guided to create their profile.
        </p>

        {{-- Warning flash --}}
        @if(session('warning'))
            <div class="se-alert se-alert-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        {{-- Validation error --}}
        @if($errors->has('query'))
            <div class="se-alert se-alert-danger">
                <i class="bi bi-x-circle-fill"></i>
                <span>{{ $errors->first('query') }}</span>
            </div>
        @endif

        {{-- Card --}}
        <div class="se-card">
            <form action="{{ route('employer.search.query') }}" method="POST">
                @csrf

                <label class="se-label" for="queryInput">NID or Employee Name</label>

                <div class="se-input-row @error('query') is-invalid @enderror">
                    <div class="se-icon-box">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <input
                        type="text"
                        id="queryInput"
                        name="query"
                        value="{{ old('query') }}"
                        class="se-input"
                        placeholder="e.g. 1199280012345678 or John Doe"
                        autofocus
                        autocomplete="off"
                    >
                    <button class="se-btn" type="submit">
                        <i class="bi bi-search"></i>
                        Search
                    </button>
                </div>

                <div class="se-hints">
                    <span class="se-pill">
                        <i class="bi bi-hash"></i>
                        NID: exact match
                    </span>
                    <span class="se-pill">
                        <i class="bi bi-person"></i>
                        Name: partial match
                    </span>
                </div>

                <hr class="se-divider">

                <div class="se-steps">
                    <div class="se-step">
                        <div class="se-step-num">1</div>
                        <div class="se-step-text">
                            <strong>Enter details</strong>
                            NID number or employee name
                        </div>
                    </div>
                    <div class="se-step">
                        <div class="se-step-num">2</div>
                        <div class="se-step-text">
                            <strong>Review results</strong>
                            Select the correct employee
                        </div>
                    </div>
                    <div class="se-step">
                        <div class="se-step-num">3</div>
                        <div class="se-step-text">
                            <strong>Not found?</strong>
                            Create a new profile
                        </div>
                    </div>
                    <div class="se-step">
                        <div class="se-step-num">4</div>
                        <div class="se-step-text">
                            <strong>Proceed</strong>
                            Continue with employment details
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection