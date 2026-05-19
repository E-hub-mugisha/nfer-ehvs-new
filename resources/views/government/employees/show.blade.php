@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' — Profile')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Back link --}}
    <a href="{{ route('government.employees.index') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i> Back to Employees
    </a>

    <div class="row g-4">

        {{-- LEFT: Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">

                {{-- Avatar --}}
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}"
                         alt="{{ $employee->first_name }}"
                         class="rounded-circle mx-auto object-fit-cover mb-3 border border-3 border-primary"
                         width="110" height="110">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
                         style="width:110px;height:110px;font-size:36px;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                <p class="text-muted small mb-3">{{ $employee->email ?? 'No email on record' }}</p>

                <span class="badge
                    {{ $employee->gender === 'Male' ? 'bg-info bg-opacity-10 text-info' : 'bg-danger bg-opacity-10 text-danger' }}
                    rounded-pill px-3 py-2">
                    {{ $employee->gender }}
                </span>

                <hr class="my-4">

                {{-- Personal Details --}}
                <ul class="list-unstyled text-start small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">NID</span>
                        <strong><code>{{ $employee->nid }}</code></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Date of Birth</span>
                        <strong>{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Age</span>
                        <strong>{{ \Carbon\Carbon::parse($employee->dob)->age }} yrs</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Phone</span>
                        <strong>{{ $employee->phone ?? '—' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">District</span>
                        <strong>{{ $employee->district ?? '—' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Sector</span>
                        <strong>{{ $employee->sector ?? '—' }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        {{-- RIGHT: Employment Records --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-briefcase me-2 text-primary"></i>
                        Employment History
                        <span class="badge bg-primary ms-2">{{ $employmentRecords->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($employmentRecords->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No employment records found.
                        </div>
                    @else
                        <div class="timeline p-4">
                            @foreach($employmentRecords as $record)
                            <div class="d-flex gap-3 mb-4">

                                {{-- Timeline dot --}}
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle bg-primary"
                                         style="width:12px;height:12px;margin-top:4px;flex-shrink:0;"></div>
                                    @if(!$loop->last)
                                        <div class="bg-primary bg-opacity-25 flex-grow-1"
                                             style="width:2px;margin-top:4px;"></div>
                                    @endif
                                </div>

                                {{-- Record Card --}}
                                <div class="card border shadow-none w-100 mb-0">
                                    <div class="card-body py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-semibold mb-0">
                                                    {{ $record->job_title ?? 'Unknown job title' }}
                                                </h6>
                                                <small class="text-muted">{{ $record->department ?? '' }}</small>
                                            </div>
                                            @if($record->end_date === null)
                                                <span class="badge bg-success">Current</span>
                                            @else
                                                <span class="badge bg-secondary">Ended</span>
                                            @endif
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
                                            @if(isset($record->salary))
                                            <div class="col-sm-6">
                                                <i class="bi bi-cash me-1"></i>
                                                <strong>Salary:</strong>
                                                RWF {{ number_format($record->salary) }}
                                            </div>
                                            @endif
                                            @if(isset($record->contract_type))
                                            <div class="col-sm-6">
                                                <i class="bi bi-file-text me-1"></i>
                                                <strong>Contract:</strong>
                                                {{ $record->contract_type }}
                                            </div>
                                            @endif
                                        </div>

                                        @if(!empty($record->notes))
                                            <div class="mt-2 p-2 bg-light rounded small text-muted">
                                                <i class="bi bi-sticky me-1"></i> {{ $record->notes }}
                                            </div>
                                        @endif
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
@endsection