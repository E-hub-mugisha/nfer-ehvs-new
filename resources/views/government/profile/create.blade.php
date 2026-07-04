@extends('layouts.app')

@section('content')
<style>
    :root {
        --gp-primary: #4f46e5;
        --gp-primary-light: #eef2ff;
        --gp-primary-dark: #3730a3;
        --gp-danger: #dc2626;
        --gp-danger-light: #fef2f2;
        --gp-info: #0284c7;
        --gp-info-light: #f0f9ff;
        --gp-border: #e3e6ee;
        --gp-muted: #8a8fa3;
        --gp-text: #1e2130;
    }

    .gp-wrap {
        max-width: 720px;
        margin: 0 auto;
    }

    .gp-header {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .gp-icon-badge {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--gp-primary), #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.3rem;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.28);
        flex-shrink: 0;
    }

    .gp-header h4 {
        font-weight: 700;
        color: var(--gp-text);
        margin-bottom: 0.15rem;
    }

    .gp-header .gp-subtitle {
        color: var(--gp-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    .gp-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(20, 20, 43, 0.06);
        overflow: hidden;
    }

    .gp-card-body {
        padding: 2rem 2.25rem 2.25rem;
    }

    .gp-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        background: var(--gp-info-light);
        border: 1px solid #e0f2fe;
        border-radius: 14px;
        padding: 0.95rem 1.1rem;
        color: #075985;
        font-size: 0.86rem;
        line-height: 1.5;
        margin-bottom: 1.75rem;
    }

    .gp-notice i {
        font-size: 1.05rem;
        color: var(--gp-info);
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    .gp-section-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--gp-muted);
        margin: 0 0 0.9rem;
    }

    .gp-section-label:not(:first-of-type) {
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gp-border);
    }

    .gp-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem;
    }

    .gp-field {
        margin-bottom: 1.1rem;
    }

    .gp-field.full {
        grid-column: 1 / -1;
    }

    .gp-field label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--gp-text);
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .gp-required {
        color: var(--gp-danger);
        font-weight: 700;
    }

    .gp-field .form-control,
    .gp-field .form-select {
        border-radius: 11px;
        border: 1px solid var(--gp-border);
        font-size: 0.9rem;
        padding: 0.65rem 0.9rem;
        color: var(--gp-text);
    }

    .gp-field .form-control:focus,
    .gp-field .form-select:focus {
        border-color: var(--gp-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .gp-field .form-control.is-invalid,
    .gp-field .form-select.is-invalid {
        border-color: var(--gp-danger);
        background-image: none;
    }

    .gp-field .invalid-feedback {
        font-size: 0.78rem;
        margin-top: 0.35rem;
    }

    .gp-hint {
        font-size: 0.76rem;
        color: var(--gp-muted);
        margin-top: 0.35rem;
    }

    .gp-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gp-border);
        flex-wrap: wrap;
    }

    .gp-footer-note {
        font-size: 0.78rem;
        color: var(--gp-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .gp-btn-submit {
        background: linear-gradient(135deg, var(--gp-primary), #6d28d9);
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 0.7rem 1.6rem;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
        transition: all 0.15s ease;
    }

    .gp-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.32);
        color: #fff;
    }

    .gp-btn-submit:disabled {
        opacity: 0.65;
        transform: none;
        cursor: not-allowed;
    }

    @media (max-width: 576px) {
        .gp-row { grid-template-columns: 1fr; }
        .gp-card-body { padding: 1.5rem 1.25rem 1.75rem; }
    }
</style>

<div class="container py-5">
    <div class="gp-wrap">

        <div class="gp-header">
            <div class="gp-icon-badge">
                <i class="bi bi-bank"></i>
            </div>
            <div>
                <h4 class="mb-0">Complete Government Profile</h4>
                <p class="gp-subtitle">This information is required before you can access the dashboard</p>
            </div>
        </div>

        <div class="gp-card">
            <div class="gp-card-body">

                <div class="gp-notice">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>Please complete your government profile before accessing the dashboard. Fields marked with <span class="gp-required">*</span> are required.</div>
                </div>

                <form action="{{ route('government.profile.store') }}" method="POST" class="gp-form" novalidate>
                    @csrf

                    <div class="gp-section-label">Institution Details</div>

                    <div class="gp-field full">
                        <label for="name">Government Name <span class="gp-required">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="e.g. Rwanda Development Board"
                            minlength="3"
                            maxlength="150"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please enter the government or institution name.</div>
                        @enderror
                    </div>

                    <div class="gp-row">
                        <div class="gp-field">
                            <label for="rdb_number">RDB Number <span class="gp-required">*</span></label>
                            <input
                                type="text"
                                id="rdb_number"
                                name="rdb_number"
                                class="form-control @error('rdb_number') is-invalid @enderror"
                                value="{{ old('rdb_number') }}"
                                placeholder="e.g. RDB-100234"
                                pattern="^[A-Za-z0-9\-\/]{3,30}$"
                                required>
                            @error('rdb_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Enter a valid RDB registration number.</div>
                            @enderror
                        </div>

                        <div class="gp-field">
                            <label for="tin_number">TIN Number <span class="gp-required">*</span></label>
                            <input
                                type="text"
                                id="tin_number"
                                name="tin_number"
                                class="form-control @error('tin_number') is-invalid @enderror"
                                value="{{ old('tin_number') }}"
                                placeholder="e.g. 100123456"
                                pattern="^[0-9]{9,12}$"
                                required>
                            @error('tin_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">TIN must be 9–12 digits.</div>
                            @enderror
                        </div>
                    </div>

                    <div class="gp-row">
                        <div class="gp-field">
                            <label for="country">Country <span class="gp-required">*</span></label>
                            <input
                                type="text"
                                id="country"
                                name="country"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', 'Rwanda') }}"
                                minlength="2"
                                maxlength="60"
                                required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please enter a country.</div>
                            @enderror
                        </div>

                        <div class="gp-field">
                            <label for="government_type">Government Type <span class="gp-required">*</span></label>
                            <select
                                id="government_type"
                                name="government_type"
                                class="form-select @error('government_type') is-invalid @enderror"
                                required>
                                <option value="" disabled {{ old('government_type') ? '' : 'selected' }}>Select Type</option>
                                <option value="Ministry" {{ old('government_type') === 'Ministry' ? 'selected' : '' }}>Ministry</option>
                                <option value="Department" {{ old('government_type') === 'Department' ? 'selected' : '' }}>Department</option>
                                <option value="Agency" {{ old('government_type') === 'Agency' ? 'selected' : '' }}>Agency</option>
                                <option value="Authority" {{ old('government_type') === 'Authority' ? 'selected' : '' }}>Authority</option>
                                <option value="National" {{ old('government_type') === 'National' ? 'selected' : '' }}>National</option>
                            </select>
                            @error('government_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please select a government type.</div>
                            @enderror
                        </div>
                    </div>

                    <div class="gp-field full">
                        <label for="established_year">Established Year</label>
                        <input
                            type="number"
                            id="established_year"
                            name="established_year"
                            class="form-control @error('established_year') is-invalid @enderror"
                            value="{{ old('established_year') }}"
                            min="1800"
                            max="{{ date('Y') }}"
                            placeholder="e.g. 2008">
                        @error('established_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Enter a valid year between 1800 and {{ date('Y') }}.</div>
                        @enderror
                    </div>

                    <div class="gp-section-label">Contact Information</div>

                    <div class="gp-field full">
                        <label for="contact_email">Contact Email <span class="gp-required">*</span></label>
                        <input
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            class="form-control @error('contact_email') is-invalid @enderror"
                            value="{{ old('contact_email') }}"
                            placeholder="info@example.gov.rw"
                            required>
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        @enderror
                    </div>

                    <div class="gp-field full">
                        <label for="website">Website</label>
                        <input
                            type="url"
                            id="website"
                            name="website"
                            class="form-control @error('website') is-invalid @enderror"
                            value="{{ old('website') }}"
                            placeholder="https://example.gov.rw"
                            pattern="https?://.+">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please enter a valid URL starting with http:// or https://.</div>
                        @enderror
                        <div class="gp-hint">Optional — include the full URL, e.g. https://minict.gov.rw</div>
                    </div>

                    <div class="gp-footer">
                        <div class="gp-footer-note">
                            <i class="bi bi-shield-check"></i> Your information is kept confidential
                        </div>
                        <button type="submit" class="gp-btn-submit" id="gpSubmitBtn">
                            <i class="bi bi-check2-circle"></i> Save Profile
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const form = document.querySelector('.gp-form');
        const submitBtn = document.getElementById('gpSubmitBtn');

        if (!form) return;

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving...';
            }

            form.classList.add('was-validated');
        }, false);

        // Live re-validation as the user fixes fields
        form.querySelectorAll('.form-control, .form-select').forEach(function (field) {
            field.addEventListener('input', function () {
                if (form.classList.contains('was-validated')) {
                    field.classList.toggle('is-invalid', !field.checkValidity());
                }
            });
            field.addEventListener('change', function () {
                if (form.classList.contains('was-validated')) {
                    field.classList.toggle('is-invalid', !field.checkValidity());
                }
            });
        });
    })();
</script>
@endsection