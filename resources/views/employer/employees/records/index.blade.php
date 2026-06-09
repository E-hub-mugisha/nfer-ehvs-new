@extends('layouts.app')
@section('title', 'My Employees')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">My Employees</h4>
            <p class="text-muted small mb-0">Select an employee to view their employment records</p>
        </div>
    </div>

    @if($employees->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                No employees linked to your account yet.
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>NID</th>
                                <th>Current Position</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                @php $latestRecord = $employee->employmentRecords->first(); @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($employee->photo)
                                                <img src="{{ asset($employee->photo) }}"
                                                     class="rounded-circle object-fit-cover"
                                                     width="40" height="40">
                                            @else
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                                            d-flex align-items-center justify-content-center fw-bold"
                                                     style="width:40px;height:40px;font-size:16px;flex-shrink:0;">
                                                    {{ strtoupper(substr($employee->first_name,0,1) . substr($employee->last_name,0,1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                <small class="text-muted">{{ $employee->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $employee->nid }}</code></td>
                                    <td>
                                        {{ $latestRecord?->job_title ?? '—' }}
                                        @if($latestRecord?->department)
                                            <br><small class="text-muted">{{ $latestRecord->department }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($latestRecord)
                                            <span class="badge rounded-pill
                                                {{ $latestRecord->employment_status === 'active'
                                                    ? 'bg-success'
                                                    : 'bg-secondary' }}">
                                                {{ ucfirst($latestRecord->employment_status) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $latestRecord?->start_date
                                            ? \Carbon\Carbon::parse($latestRecord->start_date)->format('d M Y')
                                            : '—' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('employer.employees.records.show', $employee) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-clock-history me-1"></i> View Records
                                        </a>
                                        <a href="{{ route('employer.employees.show', $employee) }}"
                                           class="btn btn-sm btn-outline-secondary ms-2">
                                           <i class="bi bi-eye me-1"></i> View Profile
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal{{ $employee->id }}">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($employees->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

{{-- Delete Employee Modals --}}
@foreach($employees as $employee)
    <div class="modal fade" id="deleteEmployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEmployeeModalLabel{{ $employee->id }}">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete employee <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('employer.employees.destroy', $employee) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection