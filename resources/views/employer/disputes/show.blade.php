@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-3">
        <a href="{{ route('employer.disputes.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h3 class="mb-4">Dispute Details</h3>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Employee:</strong><br>
                    {{ $dispute->employee->first_name ?? '' }}
                    {{ $dispute->employee->last_name ?? '' }}
                </div>

                <div class="col-md-6">
                    <strong>Job Title:</strong><br>
                    {{ $dispute->employmentRecord->job_title ?? '' }}
                </div>
            </div>

            <div class="mb-3">
                <strong>Description:</strong>

                <div class="border rounded p-3 mt-2 bg-light">
                    {{ $dispute->description }}
                </div>
            </div>

            <div class="mb-3">
                <strong>Evidence:</strong><br>

                @if($dispute->evidence)
                    <a href="{{ asset('storage/' . $dispute->evidence) }}"
                       target="_blank"
                       class="btn btn-outline-primary btn-sm mt-2">
                        View Evidence
                    </a>
                @else
                    <span class="text-muted">
                        No evidence uploaded
                    </span>
                @endif
            </div>

            <div class="mb-4">
                <strong>Current Status:</strong>

                <span class="badge bg-info">
                    {{ ucfirst($dispute->status) }}
                </span>
            </div>

            <form method="POST"
                  action="{{ route('employer.disputes.updateStatus', $dispute->id) }}">

                @csrf
                @method('PATCH')
                <div class="row">

                    <div class="col-md-4">
                        <select name="status"
                                class="form-select">

                            <option value="pending"
                                {{ $dispute->status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="resolved"
                                {{ $dispute->status == 'resolved' ? 'selected' : '' }}>
                                Resolved
                            </option>

                            <option value="rejected"
                                {{ $dispute->status == 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit"
                                class="btn btn-success">
                            Update Status
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection