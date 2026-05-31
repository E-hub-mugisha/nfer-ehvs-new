@extends('layouts.app')

@section('title', 'My Employees')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Lora:ital@1&display=swap');

    .emp-page {
        font-family: 'DM Sans', sans-serif;
        background: #F7F5F2;
        min-height: 100vh;
        padding: 2.5rem 1.5rem;
    }

    /* ── Top bar ── */
    .emp-topbar {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .emp-eyebrow {
        font-size: 11px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #9E9B95;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .emp-heading {
        font-family: 'Lora', Georgia, serif;
        font-size: 26px;
        font-weight: 400;
        font-style: italic;
        color: #1C1A17;
        margin: 0;
        line-height: 1.2;
    }

    .emp-total-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #1C1A17;
        color: #F7F5F2;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 16px;
        border-radius: 999px;
        white-space: nowrap;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Card shell ── */
    .emp-card {
        background: #FFFFFF;
        border: 1px solid #E8E5E0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    /* ── Empty state ── */
    .emp-empty {
        padding: 4rem 2rem;
        text-align: center;
        color: #A09D97;
    }

    .emp-empty i {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
        color: #C8C5BF;
    }

    .emp-empty p {
        font-size: 14px;
        margin: 0;
    }

    /* ── Table ── */
    .emp-table-wrap {
        overflow-x: auto;
    }

    .emp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
        color: #1C1A17;
    }

    .emp-table thead tr {
        background: #F7F5F2;
        border-bottom: 1px solid #E8E5E0;
    }

    .emp-table thead th {
        padding: 11px 14px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #9E9B95;
        white-space: nowrap;
        text-align: left;
    }

    .emp-table thead th.center {
        text-align: center;
    }

    .emp-table tbody tr {
        border-bottom: 1px solid #F0EDE8;
        transition: background 0.12s;
    }

    .emp-table tbody tr:last-child {
        border-bottom: none;
    }

    .emp-table tbody tr:hover {
        background: #FAFAF8;
    }

    .emp-table td {
        padding: 13px 14px;
        vertical-align: middle;
        color: #3A3731;
    }

    .emp-table td.center {
        text-align: center;
    }

    .emp-table td.muted {
        color: #A09D97;
        font-size: 13px;
    }

    /* ── Avatar ── */
    .emp-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .emp-avatar-initials {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #EAF3DE;
        color: #27500A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .emp-name-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .emp-name {
        font-weight: 500;
        font-size: 13.5px;
        color: #1C1A17;
        line-height: 1.3;
    }

    .emp-dob {
        font-size: 11.5px;
        color: #A09D97;
        margin-top: 1px;
    }

    /* ── NID code ── */
    .emp-nid {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #5F5E5A;
        background: #F7F5F2;
        border: 1px solid #E8E5E0;
        border-radius: 5px;
        padding: 3px 7px;
        white-space: nowrap;
    }

    /* ── Badges ── */
    .emp-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .emp-badge-male {
        background: #E6F1FB;
        color: #0C447C;
    }

    .emp-badge-female {
        background: #FBEAF0;
        color: #72243E;
    }

    .emp-badge-position {
        background: #EAF3DE;
        color: #27500A;
    }

    /* ── View button ── */
    .emp-btn-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        font-family: 'DM Sans', sans-serif;
        font-size: 12.5px;
        font-weight: 500;
        color: #1C1A17;
        background: #fff;
        border: 1px solid #DDD9D2;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.12s, border-color 0.12s;
        white-space: nowrap;
    }

    .emp-btn-view:hover {
        background: #F7F5F2;
        border-color: #C8C5BF;
        color: #1C1A17;
        text-decoration: none;
    }

    .emp-btn-view i {
        font-size: 13px;
    }

    /* ── Pagination ── */
    .emp-pagination {
        padding: 14px 18px;
        border-top: 1px solid #F0EDE8;
        background: #FAFAF8;
    }

    @media (max-width: 640px) {
        .emp-topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .emp-heading {
            font-size: 22px;
        }
    }
</style>

<div class="emp-page">

    {{-- Top bar --}}
    <div class="emp-topbar">
        <div>
            <p class="emp-eyebrow">{{ Auth::user()->employer->company_name }}</p>
            <h1 class="emp-heading">Employees</h1>
        </div>
        <span class="emp-total-badge">
            <i class="bi bi-people"></i>
            {{ $employees->total() }} staff
        </span>
    </div>

    {{-- Main card --}}
    <div class="emp-card">

        @if($employees->isEmpty())
        <div class="emp-empty">
            <i class="bi bi-people"></i>
            <p>No employees found for your organization.</p>
        </div>
        @else

        <div class="emp-table-wrap">
            <table class="emp-table">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Employee</th>
                        <th>NID</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>District</th>
                        <th>Position</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $employee)
                    @php
                    $latestRecord = $employee->employmentRecords->first();
                    $initials = strtoupper(
                    substr($employee->first_name, 0, 1) .
                    substr($employee->last_name, 0, 1)
                    );
                    @endphp
                    <tr>

                        {{-- Row number --}}
                        <td class="muted">
                            {{ $employees->firstItem() + $index }}
                        </td>

                        {{-- Employee name + avatar --}}
                        <td>
                            <div class="emp-name-cell">
                                @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                    alt="{{ $employee->first_name }}"
                                    class="emp-avatar">
                                @else
                                <div class="emp-avatar-initials">{{ $initials }}</div>
                                @endif
                                <div>
                                    <div class="emp-name">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </div>
                                    <div class="emp-dob">
                                        {{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- NID --}}
                        <td>
                            <span class="emp-nid">{{ $employee->nid }}</span>
                        </td>

                        {{-- Gender --}}
                        <td>
                            <span class="emp-badge {{ $employee->gender === 'Male' ? 'emp-badge-male' : 'emp-badge-female' }}">
                                <i class="bi {{ $employee->gender === 'Male' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                {{ $employee->gender }}
                            </span>
                        </td>

                        {{-- Phone --}}
                        <td class="{{ $employee->phone ? '' : 'muted' }}">
                            {{ $employee->phone ?? '—' }}
                        </td>

                        {{-- Email --}}
                        <td class="{{ $employee->email ? '' : 'muted' }}">
                            {{ $employee->email ?? '—' }}
                        </td>

                        {{-- District --}}
                        <td class="{{ $employee->district ? '' : 'muted' }}">
                            {{ $employee->district ?? '—' }}
                        </td>

                        {{-- Position --}}
                        <td>
                            @if($latestRecord && $latestRecord->job_title)
                            <span class="emp-badge emp-badge-position">
                                {{ $latestRecord->job_title }}
                            </span>
                            @else
                            <span class="muted">—</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="center">
                            <a href="{{ route('employer.employees.show', $employee) }}"
                                class="emp-btn-view">
                                <i class="bi bi-eye"></i>
                                View
                            </a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
        <div class="emp-pagination">
            {{ $employees->links() }}
        </div>
        @endif

        @endif
    </div>

</div>

@endsection