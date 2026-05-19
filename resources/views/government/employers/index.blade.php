@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Employers
            </h2>

            <p class="text-muted mb-0">
                Registered employers in the system
            </p>
        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- Employers Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            @if($employers->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>RDB Number</th>
                                <th>Status</th>
                                <th>Records</th>
                                <th width="120">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($employers as $employer)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $employer->company_name }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $employer->email }}
                                    </td>

                                    <td>
                                        {{ $employer->phone }}
                                    </td>

                                    <td>
                                        {{ $employer->rdb_number }}
                                    </td>

                                    <td>

                                        @if($employer->status == 'verified')

                                            <span class="badge bg-success">
                                                Verified
                                            </span>

                                        @elseif($employer->status == 'pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $employer->employmentRecords()->count() }}
                                    </td>

                                    <td>

                                        <a href="{{ route('government.employers.show', $employer->id) }}"
                                           class="btn btn-primary btn-sm">

                                            View Details

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $employers->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <h5 class="mb-2">
                        No employers found
                    </h5>

                    <p class="text-muted">
                        There are no registered employers yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection