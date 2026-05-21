@extends('layouts.app')
@section('title', 'Incoming Transfer Requests')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-inbox me-2 text-primary"></i>
            Incoming Transfer Requests
        </h5>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No incoming transfer requests.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Requested By</th>
                                <th>Proposed Role</th>
                                <th>Start Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $req->employee->first_name }} {{ $req->employee->last_name }}
                                    </div>
                                    <small class="text-muted">{{ $req->employee->nid }}</small>
                                </td>
                                <td>{{ $req->requestingEmployer->company_name }}</td>
                                <td>
                                    <div>{{ $req->proposed_job_title }}</div>
                                    @if($req->proposed_department)
                                        <small class="text-muted">{{ $req->proposed_department }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->proposed_start_date->format('d M Y') }}</td>
                                <td class="text-muted small" style="max-width:200px;">
                                    {{ $req->reason ?? '—' }}
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($req->isPending())
                                        {{-- Approve --}}
                                        <form action="{{ route('employer.transfer.approve', $req) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-success"
                                                    onclick="return confirm('Approve this transfer? The employee\'s current record will be closed.')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <button class="btn btn-sm btn-outline-danger ms-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $req->id }}">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>

                                        {{-- Reject Modal --}}
                                        <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">
                                                            <i class="bi bi-x-circle me-2 text-danger"></i>
                                                            Reject Transfer Request
                                                        </h6>
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('employer.transfer.reject', $req) }}"
                                                          method="POST">
                                                        @csrf @method('PATCH')
                                                        <div class="modal-body">
                                                            <label class="form-label fw-semibold">
                                                                Reason for Rejection
                                                                <span class="text-muted fw-normal">(optional)</span>
                                                            </label>
                                                            <textarea name="rejection_reason"
                                                                      class="form-control" rows="3"
                                                                      placeholder="Let the requesting employer know why...">
                                                            </textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                Confirm Rejection
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">
                                            Responded {{ $req->responded_at?->diffForHumans() }}
                                        </span>
                                    @endif
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