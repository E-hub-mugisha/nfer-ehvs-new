{{-- resources/views/disputes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Disputes')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Employment Disputes</h2>
            <p class="text-muted mb-0">
                View and manage all submitted employment disputes.
            </p>
        </div>

        <a href="{{ route('disputes.create') }}"
           class="btn btn-primary rounded-pill px-4">
            + Raise Dispute
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Disputes Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            @if($disputes->count() > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employer</th>
                                <th>Job Title</th>
                                <th>Description</th>
                                <th>Evidence</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($disputes as $dispute)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $dispute->employmentRecord->employer->company_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $dispute->employmentRecord->job_title ?? '-' }}
                                    </td>

                                    <td style="max-width: 250px;">
                                        {{ Str::limit($dispute->description, 70) }}
                                    </td>

                                    <td>
                                        @if($dispute->evidence)
                                            <a href="{{ asset('storage/'.$dispute->evidence) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary rounded-pill">
                                                View File
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                No File
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $statusColor = match($dispute->status) {
                                                'pending' => 'warning',
                                                'resolved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucfirst($dispute->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $dispute->created_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('disputes.show', $dispute->id) }}"
                                           class="btn btn-sm btn-dark rounded-pill">
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

                    <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png"
                         width="120"
                         class="mb-3">

                    <h5 class="fw-bold">
                        No Disputes Submitted
                    </h5>

                    <p class="text-muted">
                        You have not submitted any employment disputes yet.
                    </p>

                    <a href="{{ route('disputes.create') }}"
                       class="btn btn-primary rounded-pill mt-2">
                        Raise Your First Dispute
                    </a>

                </div>

            @endif

        </div>
    </div>

</div>
@endsection