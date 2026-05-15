{{-- resources/views/employment-records/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employment Records')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Employment Records</h2>
            <p class="text-muted mb-0">
                View your complete employment history and status.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            {{ session('success') }}
        </div>
    @endif

    {{-- Records Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            @if($employmentRecords->count() > 0)

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employer</th>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($employmentRecords as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $record->employer->company_name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $record->job_title }}
                                    </td>

                                    <td>
                                        {{ $record->department ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        @if($record->end_date)
                                            {{ \Carbon\Carbon::parse($record->end_date)->format('d M Y') }}
                                        @else
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $statusColor = match($record->employment_status) {
                                                'active' => 'success',
                                                'terminated' => 'danger',
                                                'resigned' => 'warning',
                                                'contract-ended' => 'secondary',
                                                default => 'primary'
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucfirst($record->employment_status) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ Str::limit($record->remarks, 40) ?? '-' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('employment-records.show', $record->id) }}"
                                           class="btn btn-sm btn-primary rounded-pill">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png"
                         width="120"
                         class="mb-3">

                    <h5 class="fw-bold">No Employment Records Found</h5>

                    <p class="text-muted">
                        Your employment history will appear here once added.
                    </p>
                </div>

            @endif

        </div>
    </div>

</div>
@endsection