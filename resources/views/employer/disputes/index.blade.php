@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Employment Disputes</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($disputes->count())

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Job Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($disputes as $dispute)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $dispute->employee->first_name ?? '' }}
                                        {{ $dispute->employee->last_name ?? '' }}
                                    </td>

                                    <td>
                                        {{ $dispute->employmentRecord->job_title ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ Str::limit($dispute->description, 50) }}
                                    </td>

                                    <td>
                                        @if($dispute->status == 'pending')
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        @elseif($dispute->status == 'resolved')
                                            <span class="badge bg-success">
                                                Resolved
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $dispute->created_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('employer.disputes.show', $dispute->id) }}"
                                           class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

                <div class="mt-3">
                    {{ $disputes->links() }}
                </div>

            @else

                <div class="text-center py-5">
                    <h5>No disputes found</h5>
                </div>

            @endif

        </div>
    </div>

</div>

@endsection