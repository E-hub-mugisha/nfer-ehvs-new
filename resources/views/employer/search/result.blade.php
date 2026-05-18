@extends('layouts.app')
@section('title', $employee->first_name . ' ' . $employee->last_name . ' — Search Result')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('employer.search.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> New Search
            </a>
            <span class="text-muted small">Employee found in the system</span>
        </div>
        @if(!$alreadyLinked)
            <a href="#addEmploymentModal" data-bs-toggle="modal" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add to My Employees
            </a>
        @else
            <span class="badge bg-success py-2 px-3">
                <i class="bi bi-check-circle me-1"></i> Already in your workforce
            </span>
        @endif
    </div>

    <div class="row g-4">

        {{-- LEFT: Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">

                @if($employee->photo)
                    <img src="{{ asset($employee->photo) }}"
                         class="rounded-circle mx-auto mb-3 border border-3 border-primary object-fit-cover"
                         width="110" height="110" alt="{{ $employee->first_name }}">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
                         style="width:110px;height:110px;font-size:36px;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                <p class="text-muted small mb-2">{{ $employee->email ?? '—' }}</p>

                <span class="badge rounded-pill px-3 py-2
                    {{ $employee->gender === 'Male' ? 'bg-info bg-opacity-10 text-info' : 'bg-danger bg-opacity-10 text-danger' }}">
                    {{ $employee->gender }}
                </span>

                <hr class="my-3">

                <ul class="list-unstyled text-start small mb-0">
                    @foreach([
                        ['NID',          '<code>' . $employee->nid . '</code>'],
                        ['Date of Birth', \Carbon\Carbon::parse($employee->dob)->format('d M Y')
                                          . ' <span class="text-muted">(' . \Carbon\Carbon::parse($employee->dob)->age . ' yrs)</span>'],
                        ['Phone',        $employee->phone   ?? '—'],
                        ['District',     $employee->district ?? '—'],
                        ['Sector',       $employee->sector   ?? '—'],
                    ] as [$label, $value])
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ $label }}</span>
                            <span class="fw-semibold text-end">{!! $value !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- RIGHT: Employment History --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-briefcase me-2 text-primary"></i>
                        Full Employment History
                        <span class="badge bg-primary ms-2">{{ $employmentRecords->count() }} Records</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($employmentRecords->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No employment records on file yet.
                        </div>
                    @else
                        <div class="p-4">
                            @foreach($employmentRecords as $record)
                            <div class="d-flex gap-3 mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle {{ $record->end_date ? 'bg-secondary' : 'bg-success' }}"
                                         style="width:12px;height:12px;margin-top:4px;flex-shrink:0;"></div>
                                    @if(!$loop->last)
                                        <div class="bg-secondary bg-opacity-25 flex-grow-1"
                                             style="width:2px;margin-top:4px;"></div>
                                    @endif
                                </div>
                                <div class="card border w-100 shadow-none mb-0">
                                    <div class="card-body py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ $record->job_title ?? 'Unknown Job Title' }}</h6>
                                                @if($record->department)
                                                    <small class="text-muted">{{ $record->department }}</small>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                @if(isset($record->contract_type))
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $record->contract_type }}
                                                    </span>
                                                @endif
                                                <span class="badge {{ $record->end_date ? 'bg-secondary' : 'bg-success' }}">
                                                    {{ $record->end_date ? 'Ended' : 'Current' }}
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="row g-2 small text-muted">
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Start:</strong>
                                                {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('d M Y') : '—' }}
                                            </div>
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                <strong>End:</strong>
                                                {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('d M Y') : 'Present' }}
                                            </div>
                                            @if(!empty($record->salary))
                                            <div class="col-sm-6">
                                                <i class="bi bi-cash me-1"></i>
                                                <strong>Salary:</strong> RWF {{ number_format($record->salary) }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Add Employment Record Modal --}}
@if(!$alreadyLinked)
<div class="modal fade" id="addEmploymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus me-2 text-primary"></i>
                    Add {{ $employee->first_name }} to Your Workforce
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('employer.search.link', $employee) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" class="form-control" required
                                   placeholder="e.g. Software Engineer">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Department</label>
                            <input type="text" name="department" class="form-control"
                                   placeholder="e.g. Engineering">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Employment Status <span class="text-danger">*</span></label>
                            <select name="employment_status" class="form-select" required>
                                <option value="">Select...</option>
                                @foreach(['active','resigned','terminated'] as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                   placeholder="e.g. 2023-12-31">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1"></i> Confirm & Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection