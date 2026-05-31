{{-- resources/views/onboarding/select-role.blade.php --}}
@extends('layouts.onboarding')

@section('title', 'Complete Your Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Page Header --}}
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-muted">Tell us who you are to complete your account setup.</p>
            </div>

            {{-- Global errors --}}
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- ── STEP 1: Role Cards (always visible until role chosen) ──────── --}}
            <div id="roleStep">
                <div class="row g-4 justify-content-center">

                    {{-- Employee --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 role-card text-center p-4"
                             style="cursor:pointer;"
                             onclick="showForm('employee')">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center
                                             rounded-circle bg-primary bg-opacity-10 text-primary"
                                      style="width:64px;height:64px;font-size:28px;">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold mb-1">Employee</h5>
                            <p class="text-muted small mb-0">
                                Looking up your employment history and records.
                            </p>
                        </div>
                    </div>

                    {{-- Employer --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 role-card text-center p-4"
                             style="cursor:pointer;"
                             onclick="showForm('employer')">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center
                                             rounded-circle bg-success bg-opacity-10 text-success"
                                      style="width:64px;height:64px;font-size:28px;">
                                    <i class="bi bi-building"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold mb-1">Employer</h5>
                            <p class="text-muted small mb-0">
                                Managing employee records for your organization.
                            </p>
                        </div>
                    </div>

                    {{-- Government --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 role-card text-center p-4"
                             style="cursor:pointer;"
                             data-bs-toggle="modal"
                             data-bs-target="#govConfirmModal">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center
                                             rounded-circle bg-warning bg-opacity-10 text-warning"
                                      style="width:64px;height:64px;font-size:28px;">
                                    <i class="bi bi-bank"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold mb-1">Government</h5>
                            <p class="text-muted small mb-0">
                                Official government oversight and verification access.
                            </p>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ── STEP 2a: Employee Profile Form ─────────────────────────────── --}}
            <div id="employeeForm" class="d-none mt-2">

                <div class="d-flex align-items-center mb-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill me-3"
                            onclick="backToRoles()">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </button>
                    <div>
                        <h4 class="fw-bold mb-0">Complete Employee Profile</h4>
                        <small class="text-muted">Fill in your personal details below.</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('onboarding.employee.store') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">

                                {{-- NID --}}
                                <div class="col-md-6">
                                    <label for="nid" class="form-label fw-semibold small">
                                        National ID (NID) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="nid" name="nid"
                                           class="form-control rounded-3 @error('nid') is-invalid @enderror"
                                           value="{{ old('nid') }}"
                                           placeholder="e.g. 1199880012345678"
                                           maxlength="16"
                                           required>
                                    @error('nid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Photo --}}
                                <div class="col-md-6">
                                    <label for="photo" class="form-label fw-semibold small">
                                        Profile Photo
                                        <span class="text-muted fw-normal">(optional)</span>
                                    </label>
                                    <input type="file"
                                           id="photo" name="photo"
                                           class="form-control rounded-3 @error('photo') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">
                                    @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- First Name --}}
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-semibold small">
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="first_name" name="first_name"
                                           class="form-control rounded-3 @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name') }}"
                                           placeholder="First name"
                                           required>
                                    @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-semibold small">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="last_name" name="last_name"
                                           class="form-control rounded-3 @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name') }}"
                                           placeholder="Last name"
                                           required>
                                    @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold small">
                                        Gender <span class="text-danger">*</span>
                                    </label>
                                    <select id="gender" name="gender"
                                            class="form-select rounded-3 @error('gender') is-invalid @enderror"
                                            required>
                                        <option value="">— Select —</option>
                                        <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date of Birth --}}
                                <div class="col-md-6">
                                    <label for="dob" class="form-label fw-semibold small">
                                        Date of Birth <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           id="dob" name="dob"
                                           class="form-control rounded-3 @error('dob') is-invalid @enderror"
                                           value="{{ old('dob') }}"
                                           required>
                                    @error('dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold small">Phone Number</label>
                                    <input type="text"
                                           id="phone" name="phone"
                                           class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="e.g. 0788123456">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email (pre-filled from user) --}}
                                <div class="col-md-6">
                                    <label for="emp_email" class="form-label fw-semibold small">Email</label>
                                    <input type="email"
                                           id="emp_email" name="email"
                                           class="form-control rounded-3 @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- District --}}
                                <div class="col-md-6">
                                    <label for="district" class="form-label fw-semibold small">District</label>
                                    <input type="text"
                                           id="district" name="district"
                                           class="form-control rounded-3 @error('district') is-invalid @enderror"
                                           value="{{ old('district') }}"
                                           placeholder="e.g. Gasabo">
                                    @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Sector --}}
                                <div class="col-md-6">
                                    <label for="sector" class="form-label fw-semibold small">Sector</label>
                                    <input type="text"
                                           id="sector" name="sector"
                                           class="form-control rounded-3 @error('sector') is-invalid @enderror"
                                           value="{{ old('sector') }}"
                                           placeholder="e.g. Kimironko">
                                    @error('sector')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill px-5">
                                    <i class="bi bi-check2-circle me-2"></i>Save & Continue
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>


            {{-- ── STEP 2b: Employer Profile Form ─────────────────────────────── --}}
            <div id="employerForm" class="d-none mt-2">

                <div class="d-flex align-items-center mb-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill me-3"
                            onclick="backToRoles()">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </button>
                    <div>
                        <h4 class="fw-bold mb-0">Complete Employer Profile</h4>
                        <small class="text-muted">Enter your organization details below.</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('onboarding.employer.store') }}">
                            @csrf

                            <div class="row g-3">

                                {{-- Company Name --}}
                                <div class="col-12">
                                    <label for="company_name" class="form-label fw-semibold small">
                                        Company Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="company_name" name="company_name"
                                           class="form-control rounded-3 @error('company_name') is-invalid @enderror"
                                           value="{{ old('company_name') }}"
                                           placeholder="e.g. Kigali Tech Ltd"
                                           required>
                                    @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- RDB Number --}}
                                <div class="col-md-6">
                                    <label for="rdb_number" class="form-label fw-semibold small">
                                        RDB Registration Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="rdb_number" name="rdb_number"
                                           class="form-control rounded-3 @error('rdb_number') is-invalid @enderror"
                                           value="{{ old('rdb_number') }}"
                                           placeholder="e.g. 100345678"
                                           required>
                                    @error('rdb_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TIN Number --}}
                                <div class="col-md-6">
                                    <label for="tin_number" class="form-label fw-semibold small">
                                        TIN Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="tin_number" name="tin_number"
                                           class="form-control rounded-3 @error('tin_number') is-invalid @enderror"
                                           value="{{ old('tin_number') }}"
                                           placeholder="e.g. 102345678"
                                           required>
                                    @error('tin_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="employer_email" class="form-label fw-semibold small">
                                        Company Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email"
                                           id="employer_email" name="email"
                                           class="form-control rounded-3 @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}"
                                           required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label for="employer_phone" class="form-label fw-semibold small">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="employer_phone" name="phone"
                                           class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="e.g. 0788123456"
                                           required>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold small">Address</label>
                                    <textarea id="address" name="address" rows="2"
                                              class="form-control rounded-3 @error('address') is-invalid @enderror"
                                              placeholder="Company physical address…">{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success rounded-pill px-5">
                                    <i class="bi bi-check2-circle me-2"></i>Save & Continue
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- ── Government Confirmation Modal ─────────────────────────────────────── --}}
<div class="modal fade" id="govConfirmModal" tabindex="-1"
     aria-labelledby="govConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header border-bottom px-4 py-3">
                <h6 class="modal-title fw-bold" id="govConfirmModalLabel">
                    <i class="bi bi-bank me-2 text-warning"></i>Government Account Confirmation
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <div class="alert alert-warning border-0 rounded-3 small mb-4">
                    <i class="bi bi-shield-lock me-1"></i>
                    Government accounts have elevated access to all employment records.
                    Please confirm this is correct before proceeding.
                </div>

                <p class="text-muted mb-1">You are registering as:</p>
                <h5 class="fw-bold mb-0">Government Official</h5>
                <p class="text-muted small mt-1">
                    This role grants read-only oversight access across the national employment registry.
                    Your account will be flagged for admin review before full access is granted.
                </p>
            </div>

            <div class="modal-footer px-4 py-3 border-top bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                        data-bs-dismiss="modal">Cancel</button>

                <form method="POST" action="{{ route('onboarding.government.store') }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm rounded-pill px-4">
                        <i class="bi bi-check2-circle me-1"></i> Yes, I'm a Government Official
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


{{-- Re-open the correct form if validation failed on POST --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('_form') === 'employee')
            showForm('employee');
        @elseif(session('_form') === 'employer')
            showForm('employer');
        @endif
    });
</script>
@endif

<script>
    function showForm(role) {
        document.getElementById('roleStep').classList.add('d-none');
        document.getElementById('employeeForm').classList.add('d-none');
        document.getElementById('employerForm').classList.add('d-none');

        if (role === 'employee') {
            document.getElementById('employeeForm').classList.remove('d-none');
        } else if (role === 'employer') {
            document.getElementById('employerForm').classList.remove('d-none');
        }
    }

    function backToRoles() {
        document.getElementById('roleStep').classList.remove('d-none');
        document.getElementById('employeeForm').classList.add('d-none');
        document.getElementById('employerForm').classList.add('d-none');
    }
</script>

<style>
    .role-card {
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .role-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
    }
</style>

@endsection