@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Back Button --}}
    <div class="mb-3">

        <a href="{{ route('government.employers.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>

    {{-- Employer Details --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start mb-4">

                <div>

                    <h2 class="fw-bold mb-1">
                        {{ $employer->company_name }}
                    </h2>

                    <p class="text-muted mb-0">
                        Employer Details
                    </p>

                </div>

                <div>

                    @if($employer->status == 'verified')

                        <span class="badge bg-success fs-6">
                            Verified
                        </span>

                    @elseif($employer->status == 'pending')

                        <span class="badge bg-warning text-dark fs-6">
                            Pending
                        </span>

                    @else

                        <span class="badge bg-danger fs-6">
                            Rejected
                        </span>

                    @endif

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>Email</strong>

                    <div>
                        {{ $employer->email }}
                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Phone</strong>

                    <div>
                        {{ $employer->phone }}
                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <strong>RDB Number</strong>

                    <div>
                        {{ $employer->rdb_number }}
                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <strong>TIN Number</strong>

                    <div>
                        {{ $employer->tin_number }}
                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <strong>Address</strong>

                    <div>
                        {{ $employer->address }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Employment Records --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="fw-bold mb-1">
                        Employment Records
                    </h4>

                    <p class="text-muted mb-0">
                        Records added by this employer
                    </p>

                </div>

            </div>

            @if($employer->employmentRecords->count())

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($employer->employmentRecords as $record)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $record->job_title }}
                                    </td>

                                    <td>
                                        {{ $record->department }}
                                    </td>

                                    <td>
                                        {{ ucfirst($record->employment_status) }}
                                    </td>

                                    <td>
                                        {{ $record->start_date }}
                                    </td>

                                    <td>
                                        {{ $record->end_date ?? 'Current' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h5>
                        No employment records found
                    </h5>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection