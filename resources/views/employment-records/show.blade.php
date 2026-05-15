{{-- resources/views/employment-records/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Employment Record Details')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Employment Record Details</h2>
            <p class="text-muted mb-0">
                Detailed employment information and employment history.
            </p>
        </div>

        <a href="{{ route('employment-records.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            ← Back
        </a>
    </div>

    <div class="row">

        {{-- Left Section --}}
        <div class="col-lg-8">

            {{-- Employment Information --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        Employment Information
                    </h5>
                </div>

                <div class="card-body px-4">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Employer
                            </label>

                            <div class="fw-semibold fs-5">
                                {{ $record->employer->company_name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Job Title
                            </label>

                            <div class="fw-semibold fs-5">
                                {{ $record->job_title }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Department
                            </label>

                            <div class="fw-semibold">
                                {{ $record->department ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Employment Status
                            </label>

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
                            <label class="text-muted small">
                                Start Date
                            </label>

                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($record->start_date)->format('d F Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                End Date
                            </label>

                            <div class="fw-semibold">
                                @if($record->end_date)
                                    {{ \Carbon\Carbon::parse($record->end_date)->format('d F Y') }}
                                @else
                                    <span class="text-success">
                                        Currently Working
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Duration
                            </label>

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
                            <label class="text-muted small">
                                Exit Reason
                            </label>

                            <div class="fw-semibold">
                                {{ $record->exit_reason ?? 'N/A' }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Remarks --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        Remarks / Notes
                    </h5>
                </div>

                <div class="card-body px-4">

                    @if($record->remarks)
                        <p class="mb-0 text-muted lh-lg">
                            {{ $record->remarks }}
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            No remarks available.
                        </p>
                    @endif

                </div>
            </div>

            {{-- Disputes --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        Employment Disputes
                    </h5>

                    <a href="#"
                       class="btn btn-sm btn-primary rounded-pill">
                        Raise Dispute
                    </a>
                </div>

                <div class="card-body px-4">

                    @if($record->disputes->count() > 0)

                        <div class="list-group list-group-flush">

                            @foreach($record->disputes as $dispute)

                                <div class="list-group-item px-0 py-3 border-bottom">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                {{ $dispute->title ?? 'Dispute Case' }}
                                            </h6>

                                            <p class="text-muted mb-2">
                                                {{ $dispute->description ?? '-' }}
                                            </p>

                                            <small class="text-muted">
                                                Submitted:
                                                {{ $dispute->created_at->format('d M Y') }}
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
                            <h6 class="fw-bold">
                                No Disputes Found
                            </h6>

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

                    <img src="{{ $record->employee->photo ?? 'https://ui-avatars.com/api/?name='.$record->employee->name }}"
                         class="rounded-circle mb-3"
                         width="100"
                         height="100"
                         style="object-fit: cover;">

                    <h5 class="fw-bold mb-1">
                        {{ $record->employee->name ?? 'Employee' }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $record->job_title }}
                    </p>

                    <div class="border-top pt-3">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">
                                Employee ID
                            </span>

                            <span class="fw-semibold">
                                #{{ $record->employee_id }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">
                                Employer
                            </span>

                            <span class="fw-semibold">
                                {{ $record->employer->company_name ?? '-' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">
                                Status
                            </span>

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
                    <h5 class="fw-bold mb-0">
                        Summary
                    </h5>
                </div>

                <div class="card-body px-4">

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Record Created
                        </small>

                        <span class="fw-semibold">
                            {{ $record->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        <span class="fw-semibold">
                            {{ $record->updated_at->diffForHumans() }}
                        </span>
                    </div>

                    <div>
                        <small class="text-muted d-block">
                            Total Disputes
                        </small>

                        <span class="fw-semibold">
                            {{ $record->disputes->count() }}
                        </span>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection