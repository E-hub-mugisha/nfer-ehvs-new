@extends('layouts.app')
@section('title', $employee->first_name . ' ' . $employee->last_name . ' — Employment Records')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('employer.employees.records.index') }}"
                class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Employees
            </a>
            <span class="text-muted small">{{ $employee->first_name }} {{ $employee->last_name }} — Employment Records</span>
        </div>
    </div>

    <div class="row g-4">

        {{-- LEFT: Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">

                @if($employee->photo)
                    <img src="{{ asset($employee->photo) }}"
                        class="rounded-circle mx-auto mb-3 border border-3 border-primary object-fit-cover"
                        width="110" height="110"
                        alt="{{ $employee->first_name }} {{ $employee->last_name }}">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
                        style="width:110px;height:110px;font-size:36px;">
                        {{ strtoupper(substr($employee->first_name,0,1) . substr($employee->last_name,0,1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                <p class="text-muted small mb-2">{{ $employee->email ?? '—' }}</p>

                <span class="badge rounded-pill px-3 py-2
                    {{ $employee->gender === 'Male'
                        ? 'bg-info bg-opacity-10 text-info'
                        : 'bg-danger bg-opacity-10 text-danger' }}">
                    {{ $employee->gender }}
                </span>

                <hr class="my-3">

                <ul class="list-unstyled text-start small mb-0">
                    @foreach([
                        ['NID',          '<code>' . $employee->nid . '</code>'],
                        ['Date of Birth', \Carbon\Carbon::parse($employee->dob)->format('d M Y')
                                          . ' <span class="text-muted">('
                                          . \Carbon\Carbon::parse($employee->dob)->age . ' yrs)</span>'],
                        ['Phone',        $employee->phone    ?? '—'],
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

        {{-- RIGHT: Employment Records Timeline --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-briefcase me-2 text-primary"></i>
                        Employment Records
                        <span class="badge bg-primary ms-2">{{ $employmentRecords->count() }}</span>
                    </h6>
                </div>

                <div class="card-body p-0">
                    @if($employmentRecords->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No records found.
                        </div>
                    @else
                        <div class="p-4">
                            @foreach($employmentRecords as $record)
                            <div class="d-flex gap-3 mb-4">

                                {{-- Timeline dot --}}
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle {{ $record->end_date ? 'bg-secondary' : 'bg-success' }}"
                                        style="width:12px;height:12px;margin-top:4px;flex-shrink:0;"></div>
                                    @if(!$loop->last)
                                        <div class="bg-secondary bg-opacity-25 flex-grow-1"
                                            style="width:2px;margin-top:4px;"></div>
                                    @endif
                                </div>

                                {{-- Record Card --}}
                                <div class="card border w-100 shadow-none mb-0">
                                    <div class="card-body py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ $record->job_title }}</h6>
                                                @if($record->department)
                                                    <small class="text-muted">{{ $record->department }}</small>
                                                @endif
                                            </div>

                                            <span class="badge {{ $record->end_date ? 'bg-secondary' : 'bg-success' }}">
                                                {{ $record->end_date ? 'Ended' : 'Current' }}
                                            </span>

                                            {{-- Edit button — targets this record's own modal by unique ID --}}
                                            <!-- <button type="button"
                                                class="btn btn-sm btn-outline-primary py-0 px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $record->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button> -->
                                            <!-- delete button -->
                                            <form action="{{ route('employer.employees.records.destroy', $record->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <hr class="my-2">

                                        <div class="row g-2 small text-muted">
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Start:</strong>
                                                {{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}
                                            </div>
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                <strong>End:</strong>
                                                {{ $record->end_date
                                                    ? \Carbon\Carbon::parse($record->end_date)->format('d M Y')
                                                    : 'Present' }}
                                            </div>
                                            <div class="col-sm-6">
                                                <i class="bi bi-activity me-1"></i>
                                                <strong>Status:</strong>
                                                {{ ucfirst($record->employment_status) }}
                                            </div>
                                            @if($record->exit_reason)
                                            <div class="col-sm-6">
                                                <i class="bi bi-box-arrow-right me-1"></i>
                                                <strong>Exit Reason:</strong>
                                                {{ ucfirst(str_replace('-', ' ', $record->exit_reason)) }}
                                            </div>
                                            @endif
                                            @if($record->remarks)
                                            <div class="col-12">
                                                <i class="bi bi-chat-left-text me-1"></i>
                                                <strong>Remarks:</strong> {{ $record->remarks }}
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


{{-- ============================================================
     Per-record Edit Modals — one modal per record, unique IDs.
     Rendered by Blade, zero JavaScript required.
     ============================================================ --}}
@foreach($employmentRecords as $record)
<div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1"
     aria-labelledby="editModalLabel{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom px-4 py-3">
                <h6 class="modal-title fw-bold" id="editModalLabel{{ $record->id }}">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Employment Record
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST"
                  action="{{ route('employer.employees.records.update', ['record' => $record->id]) }}">
                @csrf
                @method('PUT')

                <div class="modal-body px-4 py-4">

                    @if($errors->any())
                    <div class="alert alert-danger small py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row g-3">

                        {{-- Job Title --}}
                        <div class="col-sm-6">
                            <label for="job_title_{{ $record->id }}" class="form-label fw-semibold small">
                                Job Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                id="job_title_{{ $record->id }}"
                                name="job_title"
                                class="form-control @error('job_title') is-invalid @enderror"
                                value="{{ old('job_title', $record->job_title) }}"
                                placeholder="e.g. Software Engineer"
                                required>
                            @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-sm-6">
                            <label for="department_{{ $record->id }}" class="form-label fw-semibold small">
                                Department
                            </label>
                            <input type="text"
                                id="department_{{ $record->id }}"
                                name="department"
                                class="form-control @error('department') is-invalid @enderror"
                                value="{{ old('department', $record->department) }}"
                                placeholder="e.g. Engineering">
                            @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="col-sm-6">
                            <label for="start_date_{{ $record->id }}" class="form-label fw-semibold small">
                                Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                id="start_date_{{ $record->id }}"
                                name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('Y-m-d') : '') }}"
                                required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- End Date --}}
                        <div class="col-sm-6">
                            <label for="end_date_{{ $record->id }}" class="form-label fw-semibold small">
                                End Date
                                <span class="text-muted fw-normal">(leave blank if current)</span>
                            </label>
                            <input type="date"
                                id="end_date_{{ $record->id }}"
                                name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('Y-m-d') : '') }}">
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- remark --}}
                        <div class="col-12">
                            <label for="remark_{{ $record->id }}" class="form-label fw-semibold small">remark</label>
                            <textarea id="remark_{{ $record->id }}"
                                name="remark"
                                class="form-control @error('remark') is-invalid @enderror"
                                rows="3"
                                placeholder="Any additional remark…">{{ old('remark', $record->remark) }}</textarea>
                            @error('remark')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 py-3 border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach

@endsection