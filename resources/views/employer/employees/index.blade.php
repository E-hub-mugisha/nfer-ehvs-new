@extends('layouts.app')

@section('title', 'My Employees')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Employees</h4>
            <small class="text-muted">All staff linked to your organization</small>
        </div>
        <span class="badge bg-primary fs-6">{{ $employees->total() }} Total</span>
    </div>

    {{-- Employee Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($employees->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                    No employees found for your organization.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Employee</th>
                                <th>NID</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>District</th>
                                <th>Latest Position</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                            @php
                                $latestRecord = $employee->employmentRecords->first();
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted">
                                    {{ $employees->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}"
                                                 alt="{{ $employee->first_name }}"
                                                 class="rounded-circle object-fit-cover"
                                                 width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                                        d-flex align-items-center justify-content-center fw-bold"
                                                 style="width:40px;height:40px;font-size:15px;">
                                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </div>
                                            <small class="text-muted">DOB: {{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-dark">{{ $employee->nid }}</code>
                                </td>
                                <td>
                                    <span class="badge rounded-pill
                                        {{ $employee->gender === 'Male' ? 'bg-info bg-opacity-10 text-info' : 'bg-pink bg-opacity-10 text-danger' }}">
                                        {{ $employee->gender }}
                                    </span>
                                </td>
                                <td>{{ $employee->phone ?? '—' }}</td>
                                <td>{{ $employee->email ?? '—' }}</td>
                                <td>{{ $employee->district ?? '—' }}</td>
                                <td>
                                    @if($latestRecord)
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $latestRecord->position ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('employer.employees.show', $employee) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($employees->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $employees->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>
@endsection