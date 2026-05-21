{{-- resources/views/employer/transfer-requests/search.blade.php --}}

@extends('layouts.employer')

@section('title', 'Request Employee Transfer')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Request Employee Transfer</h4>
            <p class="text-muted small mb-0">Search for an employee by National ID to initiate a transfer request</p>
        </div>
        <a href="{{ route('employer.transfer-requests.sent') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-send me-1"></i> My Sent Requests
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search Card --}}
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:64px;height:64px;">
                            <i class="bi bi-person-search fs-3 text-primary"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Find Employee</h5>
                        <p class="text-muted small">Enter the employee's 16-digit National ID number</p>
                    </div>

                    <form action="{{ route('employer.transfer-requests.search') }}" method="GET">
                        <div class="mb-3">
                            <label for="nid" class="form-label fw-medium">National ID (NID)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-credit-card text-muted"></i>
                                </span>
                                <input
                                    type="text"
                                    name="nid"
                                    id="nid"
                                    class="form-control @error('nid') is-invalid @enderror"
                                    placeholder="e.g. 1199880012345678"
                                    value="{{ old('nid', request('nid')) }}"
                                    maxlength="16"
                                    required
                                >
                                @error('nid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Search Employee
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Note --}}
            <div class="card border-0 bg-light mt-3">
                <div class="card-body py-3 px-4">
                    <h6 class="fw-semibold small mb-2 text-muted text-uppercase ls-1">How it works</h6>
                    <ol class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Search for the employee by their National ID</li>
                        <li class="mb-1">Fill in the proposed job details and start date</li>
                        <li class="mb-1">The current employer will receive and review your request</li>
                        <li>Once approved, the employment record is automatically updated</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection