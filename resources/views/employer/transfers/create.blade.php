{{-- resources/views/employer/transfer-requests/create.blade.php --}}

@extends('layouts.employer')

@section('title', 'Submit Transfer Request')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Submit Transfer Request</h4>
            <p class="text-muted small mb-0">Propose employment terms for this employee at your company</p>
        </div>
        <a href="{{ route('employer.transfer-requests.search') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Search
        </a>
    </div>

    <div class="row g-4">

        {{-- Left: Employee + Current Employment Info --}}
        <div class="col-lg-4">

            {{-- Employee Card --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-person-fill text-primary me-2"></i>Employee
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if($employee->photo)
                            <img src="{{ asset('image/' . $employee->photo) }}"
                                 class="rounded-circle object-fit-cover"
                                 style="width:56px;height:56px;" alt="">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:56px;height:56px;">
                                <i class="bi bi-person-fill text-primary fs-4"></i>
                            </div>
                        @endif
                        <div>
                            <p class="fw-semibold mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                            <span class="text-muted small">{{ $employee->nid }}</span>
                        </div>
                    </div>

                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Gender</span>
                            <span class="fw-medium text-capitalize">{{ $employee->gender }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Date of Birth</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Phone</span>
                            <span class="fw-medium">{{ $employee->phone }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Location</span>
                            <span class="fw-medium">{{ $employee->district }}, {{ $employee->sector }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Current Employment Card --}}
            <div class="card border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-building text-warning me-2"></i>Current Employment
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Company</span>
                            <span class="fw-semibold">{{ $activeRecord->employer->company_name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Job Title</span>
                            <span class="fw-medium">{{ $activeRecord->job_title }}</span>
                        </li>
                        @if($activeRecord->department)
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Department</span>
                            <span class="fw-medium">{{ $activeRecord->department }}</span>
                        </li>
                        @endif
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Since</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($activeRecord->start_date)->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- Right: Request Form --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>Proposed Employment Terms
                    </h6>
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('employer.transfer-requests.store') }}" method="POST">
                        @csrf

                        {{-- Hidden fields --}}
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="current_employment_record_id" value="{{ $activeRecord->id }}">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Proposed Job Title <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="proposed_job_title"
                                    class="form-control @error('proposed_job_title') is-invalid @enderror"
                                    value="{{ old('proposed_job_title') }}"
                                    placeholder="e.g. Software Engineer"
                                    required
                                >
                                @error('proposed_job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Department</label>
                                <input
                                    type="text"
                                    name="proposed_department"
                                    class="form-control @error('proposed_department') is-invalid @enderror"
                                    value="{{ old('proposed_department') }}"
                                    placeholder="e.g. Engineering"
                                >
                                @error('proposed_department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Proposed Start Date <span class="text-danger">*</span></label>
                                <input
                                    type="date"
                                    name="proposed_start_date"
                                    class="form-control @error('proposed_start_date') is-invalid @enderror"
                                    value="{{ old('proposed_start_date') }}"
                                    min="{{ now()->toDateString() }}"
                                    required
                                >
                                @error('proposed_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">The new employment record will start on this date.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Reason for Transfer Request</label>
                                <textarea
                                    name="reason"
                                    class="form-control @error('reason') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Briefly explain why you are requesting this transfer..."
                                >{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Notice --}}
                        <div class="alert alert-info d-flex gap-2 mt-4 mb-0">
                            <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                            <div class="small">
                                <strong>What happens next?</strong><br>
                                Your request will be sent to <strong>{{ $activeRecord->employer->company_name }}</strong> for review.
                                Once they approve, the employee's current record will be closed and a new employment record
                                will be created under your company automatically.
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-2"></i>Submit Transfer Request
                            </button>
                            <a href="{{ route('employer.transfer-requests.search') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection