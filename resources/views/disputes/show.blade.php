{{-- resources/views/disputes/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Dispute Details')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Dispute Details
            </h2>

            <p class="text-muted mb-0">
                Detailed information about your submitted dispute.
            </p>
        </div>

        <a href="{{ route('disputes.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            ← Back
        </a>

    </div>

    <div class="row">

        {{-- Main Content --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        Dispute Information
                    </h5>
                </div>

                <div class="card-body px-4">

                    <div class="mb-4">
                        <label class="text-muted small">
                            Employer
                        </label>

                        <div class="fw-semibold fs-5">
                            {{ $dispute->employmentRecord->employer->company_name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">
                            Job Title
                        </label>

                        <div class="fw-semibold">
                            {{ $dispute->employmentRecord->job_title ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">
                            Description
                        </label>

                        <div class="bg-light rounded-4 p-3 mt-2">
                            {{ $dispute->description }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">
                            Evidence
                        </label>

                        <div class="mt-2">

                            @if($dispute->evidence)

                                <a href="{{ asset('storage/'.$dispute->evidence) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary rounded-pill">
                                    View Evidence File
                                </a>

                            @else

                                <p class="text-muted mb-0">
                                    No evidence uploaded.
                                </p>

                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Dispute Status
                    </h5>

                    @php
                        $statusColor = match($dispute->status) {
                            'pending' => 'warning',
                            'resolved' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary'
                        };
                    @endphp

                    <div class="mb-4">
                        <span class="badge bg-{{ $statusColor }} px-3 py-2">
                            {{ ucfirst($dispute->status) }}
                        </span>
                    </div>

                    <div class="border-top pt-3">

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Submitted Date
                            </small>

                            <span class="fw-semibold">
                                {{ $dispute->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <span class="fw-semibold">
                                {{ $dispute->updated_at->diffForHumans() }}
                            </span>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Employee ID
                            </small>

                            <span class="fw-semibold">
                                #{{ $dispute->employee_id }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection