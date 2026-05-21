@extends('layouts.app')
@section('title', 'Sent Transfer Requests')

@section('content')
<div class="container-fluid px-4 py-4">
    <h5 class="fw-bold mb-4">
        <i class="bi bi-send me-2 text-primary"></i>
        Sent Transfer Requests
    </h5>

    @if(session('success'))
        <div class="alert alert-success d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 text-muted">
            <i class="bi bi-send fs-1 d-block mb-2"></i>
            You haven't sent any transfer requests yet.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Current Employer</th>
                                <th>Proposed Role</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Sent</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $req->employee->first_name }} {{ $req->employee->last_name }}
                                    </div>
                                </td>
                                <td>{{ $req->currentEmployer->company_name }}</td>
                                <td>
                                    {{ $req->proposed_job_title }}
                                    @if($req->proposed_department)
                                        <br><small class="text-muted">{{ $req->proposed_department }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->proposed_start_date->format('d M Y') }}</td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-muted small" style="max-width:180px;">
                                    @if($req->status === 'rejected' && $req->rejection_reason)
                                        <span class="text-danger">{{ $req->rejection_reason }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $req->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">{{ $requests->links() }}</div>
    @endif
</div>
@endsection