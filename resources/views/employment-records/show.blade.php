{{-- resources/views/employment-records/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Employment Record Details')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Employment Record Details</h2>
            <p class="text-muted mb-0">Detailed employment information and employment history.</p>
        </div>
        <a href="{{ route('my.employment-records.index') }}"
            class="btn btn-outline-secondary rounded-pill">← Back</a>
    </div>

    {{-- Validation errors (shown when modal was submitted and failed) --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">

        {{-- Left Section --}}
        <div class="col-lg-8">

            {{-- Employment Information --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Employment Information</h5>
                </div>
                <div class="card-body px-4">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">Employer</label>
                            <div class="fw-semibold fs-5">{{ $record->employer->company_name ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Job Title</label>
                            <div class="fw-semibold fs-5">{{ $record->job_title }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Department</label>
                            <div class="fw-semibold">{{ $record->department ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Employment Status</label>
                            <div>
                                @php
                                $statusColor = match($record->employment_status) {
                                'active' => 'success',
                                'terminated' => 'danger',
                                'resigned' => 'warning',
                                'contract-ended' => 'secondary',
                                default => 'primary'
                                };
                                @endphp
                                <span class="badge bg-{{ $statusColor }} px-3 py-2">
                                    {{ ucfirst($record->employment_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Start Date</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($record->start_date)->format('d F Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">End Date</label>
                            <div class="fw-semibold">
                                @if($record->end_date)
                                {{ \Carbon\Carbon::parse($record->end_date)->format('d F Y') }}
                                @else
                                <span class="text-success">Currently Working</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Duration</label>
                            <div class="fw-semibold">
                                @php
                                $start = \Carbon\Carbon::parse($record->start_date);
                                $end = $record->end_date
                                ? \Carbon\Carbon::parse($record->end_date)
                                : now();
                                @endphp
                                {{ $start->diffInMonths($end) }} Months
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Exit Reason</label>
                            <div class="fw-semibold">{{ $record->exit_reason ?? 'N/A' }}</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Remarks --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Remarks / Notes</h5>
                </div>
                <div class="card-body px-4">
                    @if($record->remarks)
                    <p class="mb-0 text-muted lh-lg">{{ $record->remarks }}</p>
                    @else
                    <p class="text-muted mb-0">No remarks available.</p>
                    @endif
                </div>
            </div>

            {{-- Disputes --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Employment Disputes</h5>

                    {{-- Trigger button — opens the Raise Dispute modal --}}
                    <button type="button"
                        class="btn btn-sm btn-primary rounded-pill"
                        data-bs-toggle="modal"
                        data-bs-target="#raiseDisputeModal">
                        <i class="bi bi-plus-circle me-1"></i> Raise Dispute
                    </button>
                </div>

                <div class="card-body px-4">
                    @if($record->disputes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($record->disputes as $dispute)
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $dispute->title ?? 'Dispute Case' }}</h6>
                                    <p class="text-muted mb-2 small">{{ $dispute->description ?? '-' }}</p>
                                    <small class="text-muted">
                                        Submitted: {{ $dispute->created_at->format('d M Y') }}
                                    </small>
                                </div>
                                <div>
                                    @php
                                    $disputeColor = match($dispute->status ?? 'pending') {
                                    'resolved' => 'success',
                                    'rejected' => 'danger',
                                    'pending' => 'warning',
                                    default => 'secondary'
                                    };
                                    @endphp
                                    <span class="badge bg-{{ $disputeColor }}">
                                        {{ ucfirst($dispute->status ?? 'Pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-shield-exclamation fs-1 text-muted d-block mb-2"></i>
                        <h6 class="fw-bold">No Disputes Found</h6>
                        <p class="text-muted mb-0">
                            There are no disputes associated with this employment record.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="col-lg-4">

            {{-- Employee Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center p-4">

                    <img src="{{ $record->employee->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($record->employee->name ?? 'Employee') }}"
                        class="rounded-circle mb-3"
                        width="100" height="100"
                        style="object-fit:cover;"
                        alt="{{ $record->employee->name ?? 'Employee' }}">

                    <h5 class="fw-bold mb-1">{{ $record->employee->name ?? 'Employee' }}</h5>
                    <p class="text-muted mb-3">{{ $record->job_title }}</p>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Employee ID</span>
                            <span class="fw-semibold">#{{ $record->employee_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Employer</span>
                            <span class="fw-semibold">{{ $record->employer->company_name ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <span class="fw-semibold text-success">
                                {{ ucfirst($record->employment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employment Summary --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Summary</h5>
                </div>
                <div class="card-body px-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Record Created</small>
                        <span class="fw-semibold">{{ $record->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <span class="fw-semibold">{{ $record->updated_at->diffForHumans() }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Disputes</small>
                        <span class="fw-semibold">{{ $record->disputes->count() }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


{{-- ============================================================
     Raise Dispute Modal
     ============================================================ --}}
<div class="modal fade" id="raiseDisputeModal" tabindex="-1"
    aria-labelledby="raiseDisputeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header border-bottom px-4 py-3">
                <h6 class="modal-title fw-bold" id="raiseDisputeModalLabel">
                    <i class="bi bi-shield-exclamation me-2 text-danger"></i>Raise a Dispute
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{--
                Route example:
                Route::post('employment-records/{record}/disputes', [DisputeController::class, 'store'])
                     ->name('my.disputes.store');

                Controller should validate, store, and redirect back with session('success').
            --}}
            <form method="POST"
                action="{{ route('my.disputes.store', $record->id) }}"
                enctype="multipart/form-data">
                @csrf

                {{-- Hidden fields — pre-filled from the current record/employee --}}
                <input type="hidden" name="employee_id" value="{{ $record->employee_id }}">
                <input type="hidden" name="employment_record_id" value="{{ $record->id }}">

                <div class="modal-body px-4 py-4">

                    <div class="alert alert-warning border-0 rounded-3 small py-2 mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        Please provide an accurate description of your dispute. Disputes are reviewed
                        by our team and may take up to <strong>7 business days</strong> to resolve.
                    </div>

                    <div class="row g-3">

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="dispute_description" class="form-label fw-semibold small">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="dispute_description"
                                name="description"
                                rows="5"
                                class="form-control rounded-3 @error('description') is-invalid @enderror"
                                placeholder="Describe the dispute in detail — what happened, when, and why you believe it is incorrect…"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                Minimum 20 characters. Be as specific as possible.
                            </div>
                        </div>

                        {{-- Evidence upload --}}
                        <div class="col-12">
                            <label for="dispute_evidence" class="form-label fw-semibold small">
                                Supporting Evidence
                                <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <input type="file"
                                id="dispute_evidence"
                                name="evidence"
                                class="form-control rounded-3 @error('evidence') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            @error('evidence')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                Accepted formats: PDF, JPG, PNG, DOC, DOCX. Max size: 5MB.
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 py-3 border-top bg-light rounded-bottom-4">
                    <button type="button"
                        class="btn btn-outline-secondary btn-sm rounded-pill"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">
                        <i class="bi bi-send me-1"></i> Submit Dispute
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{--
    Auto-open the modal on page load if validation failed on dispute submission.
    This re-opens the modal with errors visible without any extra JavaScript event listeners.
--}}
@if($errors->any())
<div class="modal fade show d-block" id="raiseDisputeModalAutoOpen"
    style="background:rgba(0,0,0,.5)" tabindex="-1" aria-hidden="true">
    {{-- Handled by the existing #raiseDisputeModal above --}}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('raiseDisputeModal'));
        modal.show();
    });
</script>
@endif

@endsection