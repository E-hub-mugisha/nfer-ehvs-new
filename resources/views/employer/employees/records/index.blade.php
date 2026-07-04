@extends('layouts.app')
@section('title', 'My Employees')

@section('content')
<style>
    :root {
        --emp-primary: #4f46e5;
        --emp-primary-light: #eef2ff;
        --emp-primary-dark: #3730a3;
        --emp-success: #16a34a;
        --emp-success-light: #ecfdf3;
        --emp-danger: #dc2626;
        --emp-danger-light: #fef2f2;
        --emp-muted-status: #6b7280;
        --emp-muted-status-light: #f3f4f6;
        --emp-border: #eef0f4;
        --emp-muted: #8a8fa3;
        --emp-text: #1e2130;
    }

    .emp-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .emp-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .emp-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--emp-primary), #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.15rem;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.28);
        flex-shrink: 0;
    }

    .emp-title-wrap h5 {
        font-weight: 700;
        color: var(--emp-text);
        margin-bottom: 0.1rem;
    }

    .emp-title-wrap .emp-subtitle {
        color: var(--emp-muted);
        font-size: 0.82rem;
    }

    .emp-count-chip {
        background: var(--emp-primary-light);
        color: var(--emp-primary);
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .emp-card {
        border: none;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(20, 20, 43, 0.05);
        overflow: hidden;
    }

    .emp-empty {
        padding: 4rem 1rem;
        text-align: center;
    }

    .emp-empty i {
        font-size: 2.75rem;
        color: #d7dae3;
        display: block;
        margin-bottom: 0.75rem;
    }

    .emp-empty p {
        color: var(--emp-muted);
        font-weight: 500;
        margin: 0;
    }

    .emp-table {
        margin-bottom: 0;
        width: 100%;
    }

    .emp-table thead th {
        background: #fafbfc;
        color: var(--emp-muted);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--emp-border);
        padding: 0.95rem 1.1rem;
        white-space: nowrap;
    }

    .emp-table tbody td {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid var(--emp-border);
        vertical-align: middle;
    }

    .emp-table tbody tr:last-child td {
        border-bottom: none;
    }

    .emp-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .emp-table tbody tr:hover {
        background-color: #fafbfe;
    }

    .emp-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--emp-primary-light);
        color: var(--emp-primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .emp-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .emp-employee-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .emp-employee-name {
        font-weight: 600;
        color: var(--emp-text);
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .emp-employee-email {
        color: var(--emp-muted);
        font-size: 0.76rem;
    }

    .emp-nid {
        display: inline-block;
        background: #fafbfc;
        border: 1px solid var(--emp-border);
        color: var(--emp-text);
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.78rem;
        padding: 0.25rem 0.55rem;
        border-radius: 7px;
    }

    .emp-position-title {
        font-weight: 600;
        color: var(--emp-text);
        font-size: 0.88rem;
    }

    .emp-position-dept {
        color: var(--emp-muted);
        font-size: 0.76rem;
    }

    .emp-date {
        font-size: 0.85rem;
        color: var(--emp-text);
        font-weight: 500;
        white-space: nowrap;
    }

    .emp-empty-cell {
        color: var(--emp-muted);
        font-size: 0.85rem;
    }

    .emp-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .emp-status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .emp-status-active {
        background: var(--emp-success-light);
        color: var(--emp-success);
    }
    .emp-status-active::before { background: var(--emp-success); }

    .emp-status-inactive {
        background: var(--emp-muted-status-light);
        color: var(--emp-muted-status);
    }
    .emp-status-inactive::before { background: var(--emp-muted-status); }

    .emp-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .emp-btn {
        border: none;
        border-radius: 9px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.45rem 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.15s ease;
        white-space: nowrap;
        text-decoration: none;
    }

    .emp-btn-records {
        background: var(--emp-primary-light);
        color: var(--emp-primary);
    }
    .emp-btn-records:hover { background: var(--emp-primary); color: #fff; }

    .emp-btn-profile {
        background: #f4f5f8;
        color: var(--emp-text);
    }
    .emp-btn-profile:hover { background: #e7e9ef; color: var(--emp-text); }

    .emp-btn-delete {
        background: var(--emp-danger-light);
        color: var(--emp-danger);
    }
    .emp-btn-delete:hover { background: var(--emp-danger); color: #fff; }

    .emp-pagination-wrap {
        border-top: 1px solid var(--emp-border);
        padding: 1rem 1.25rem;
    }

    /* Delete confirmation modal */
    .emp-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
    }

    .emp-modal .modal-header {
        border-bottom: 1px solid var(--emp-border);
        padding: 1.25rem 1.5rem;
    }

    .emp-modal .modal-header h6 {
        font-weight: 700;
        color: var(--emp-text);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
    }

    .emp-modal-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
        background: var(--emp-danger-light);
        color: var(--emp-danger);
    }

    .emp-modal .modal-body {
        padding: 1.5rem;
        font-size: 0.9rem;
        color: var(--emp-text);
        line-height: 1.55;
    }

    .emp-modal .modal-footer {
        border-top: 1px solid var(--emp-border);
        padding: 1rem 1.5rem;
    }

    .emp-modal .btn-cancel {
        background: #f4f5f8;
        color: var(--emp-muted);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.55rem 1.1rem;
    }

    .emp-modal .btn-confirm-delete {
        background: var(--emp-danger);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.55rem 1.2rem;
    }

    @media (max-width: 992px) {
        .emp-table thead th:nth-child(2) { display: none; }
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="emp-page-header">
        <div class="emp-title-wrap">
            <div class="emp-icon-badge">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <h5 class="mb-0">My Employees</h5>
                <div class="emp-subtitle">Select an employee to view their employment records</div>
            </div>
        </div>
        @if(!$employees->isEmpty())
            <span class="emp-count-chip">{{ $employees->total() ?? $employees->count() }} total</span>
        @endif
    </div>

    @if($employees->isEmpty())
        <div class="emp-card">
            <div class="emp-empty">
                <i class="bi bi-people"></i>
                <p>No employees linked to your account yet.</p>
            </div>
        </div>
    @else
        <div class="emp-card">
            <div class="table-responsive">
                <table class="table emp-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Current Position</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            @php $latestRecord = $employee->employmentRecords->first(); @endphp
                            <tr>
                                <td>
                                    <div class="emp-employee-cell">
                                        <div class="emp-avatar">
                                            @if($employee->photo)
                                                <img src="{{ asset($employee->photo) }}" alt="">
                                            @else
                                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="emp-employee-name">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </div>
                                            <div class="emp-employee-email">{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($latestRecord)
                                        <div class="emp-position-title">{{ $latestRecord->job_title }}</div>
                                        @if($latestRecord->department)
                                            <div class="emp-position-dept">{{ $latestRecord->department }}</div>
                                        @endif
                                    @else
                                        <span class="emp-empty-cell">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($latestRecord)
                                        <span class="emp-status-pill {{ $latestRecord->employment_status === 'active' ? 'emp-status-active' : 'emp-status-inactive' }}">
                                            {{ ucfirst($latestRecord->employment_status) }}
                                        </span>
                                    @else
                                        <span class="emp-empty-cell">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($latestRecord?->start_date)
                                        <span class="emp-date">{{ \Carbon\Carbon::parse($latestRecord->start_date)->format('d M Y') }}</span>
                                    @else
                                        <span class="emp-empty-cell">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="emp-actions">
                                        <a href="{{ route('employer.employees.records.show', $employee) }}"
                                           class="emp-btn emp-btn-records">
                                            <i class="bi bi-clock-history"></i> Records
                                        </a>
                                        <a href="{{ route('employer.employees.show', $employee) }}"
                                           class="emp-btn emp-btn-profile">
                                            <i class="bi bi-eye"></i> Profile
                                        </a>
                                        <button class="emp-btn emp-btn-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal{{ $employee->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($employees->hasPages())
                <div class="emp-pagination-wrap">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

{{-- Delete Employee Modals --}}
@foreach($employees as $employee)
    <div class="modal fade emp-modal" id="deleteEmployeeModal{{ $employee->id }}" tabindex="-1"
         aria-labelledby="deleteEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 id="deleteEmployeeModalLabel{{ $employee->id }}">
                        <span class="emp-modal-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </span>
                        Confirm Deletion
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete employee
                    <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>?
                    This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('employer.employees.destroy', $employee) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-confirm-delete text-white">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection