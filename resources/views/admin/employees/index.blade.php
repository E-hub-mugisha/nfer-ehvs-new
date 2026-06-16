@extends('layouts.app')

@section('title', 'Employees')

@section('content')

<style>
    .emp-stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(14,32,57,.05);
        transition: box-shadow .2s;
    }
    .emp-stat-card:hover { box-shadow: 0 4px 18px rgba(14,32,57,.1); }

    .emp-stat-icon {
        width: 48px; height: 48px;
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .esi-total    { background: rgba(14,32,57,.07);  color: var(--navy); }
    .esi-male     { background: rgba(59,130,246,.1); color: #1d4ed8; }
    .esi-female   { background: rgba(236,72,153,.1); color: #be185d; }
    .esi-active   { background: rgba(16,185,129,.1); color: #059669; }

    .emp-stat-value {
        font-family: 'Sora', sans-serif;
        font-size: 24px; font-weight: 700;
        color: var(--navy); line-height: 1;
    }
    .emp-stat-label {
        font-size: 12px; color: var(--text-muted);
        margin-top: 3px; text-transform: uppercase;
        letter-spacing: 0.6px; font-weight: 600;
    }

    .filters-bar {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 20px;
        display: flex; align-items: center;
        gap: 12px; flex-wrap: wrap;
        box-shadow: 0 1px 4px rgba(14,32,57,.04);
    }
    .search-wrap { position: relative; flex: 1; min-width: 220px; }
    .search-wrap i {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted); font-size: 15px; pointer-events: none;
    }
    .search-inp {
        width: 100%;
        border: 1px solid var(--border); border-radius: 10px;
        padding: 9px 14px 9px 38px;
        font-size: 14px; font-family: 'DM Sans', sans-serif;
        color: #1a2e45; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-inp:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-pale);
    }
    .filter-sel {
        border: 1px solid var(--border); border-radius: 10px;
        padding: 9px 14px; font-size: 14px;
        font-family: 'DM Sans', sans-serif; color: #1a2e45;
        outline: none; background: #fff; cursor: pointer;
        transition: border-color .2s;
    }
    .filter-sel:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-pale);
    }

    .table-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(14,32,57,.06);
        overflow: hidden;
    }
    .table-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: #fafbfc;
    }
    .table-card-header h6 {
        font-family: 'Sora', sans-serif; font-size: 13px;
        font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.8px; color: var(--text-muted); margin: 0;
    }

    .emp-table { font-size: 13.5px; }
    .emp-table thead th {
        font-family: 'Sora', sans-serif; font-size: 10px;
        font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.8px; color: var(--text-muted);
        background: #f8fafc; border-bottom: 2px solid var(--border);
        padding: 13px 16px; white-space: nowrap;
    }
    .emp-table tbody td {
        padding: 13px 16px;
        border-bottom: 1px solid rgba(14,32,57,.05);
        vertical-align: middle;
    }
    .emp-table tbody tr:last-child td { border-bottom: none; }
    .emp-table tbody tr { transition: background .15s; }
    .emp-table tbody tr:hover { background: rgba(212,148,58,.03); }

    .emp-avatar {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Sora', sans-serif; font-weight: 700;
        font-size: 14px; flex-shrink: 0; overflow: hidden;
    }
    .emp-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .emp-avatar-male   { background: rgba(59,130,246,.12); color: #1d4ed8; }
    .emp-avatar-female { background: rgba(236,72,153,.12); color: #be185d; }

    .gender-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .gender-male   { background: rgba(59,130,246,.1);  color: #1d4ed8; }
    .gender-female { background: rgba(236,72,153,.1); color: #be185d; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .sp-active     { background: rgba(16,185,129,.1);  color: #065f46; }
    .sp-inactive   { background: rgba(107,114,128,.1); color: #374151; }
    .sp-terminated { background: rgba(239,68,68,.1);   color: #991b1b; }
    .sp-resigned   { background: rgba(234,179,8,.1);   color: #854d0e; }

    .btn-view {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--navy-dark); color: #fff; border: none;
        border-radius: 8px; padding: 7px 14px;
        font-size: 12px; font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none; transition: all .2s; white-space: nowrap;
    }
    .btn-view:hover {
        background: var(--navy); color: var(--gold);
        transform: translateY(-1px);
    }

    .nid-code {
        font-family: 'Sora', sans-serif; font-size: 11px;
        font-weight: 600; color: var(--navy);
        background: rgba(14,32,57,.06);
        padding: 3px 8px; border-radius: 6px;
        letter-spacing: 0.5px;
    }

    .empty-state { padding: 64px 24px; text-align: center; }
    .empty-icon {
        width: 72px; height: 72px; border-radius: 20px;
        background: rgba(14,32,57,.05);
        display: flex; align-items: center; justify-content: center;
        font-size: 30px; color: var(--text-muted);
        margin: 0 auto 16px;
    }

    .pagination .page-link {
        border-radius: 8px !important;
        border: 1px solid var(--border);
        color: var(--navy); font-size: 13px;
        padding: 6px 12px; margin: 0 2px;
        font-family: 'DM Sans', sans-serif; font-weight: 500;
    }
    .pagination .page-item.active .page-link {
        background: var(--navy-dark); border-color: var(--navy-dark); color: #fff;
    }
    .pagination .page-link:hover {
        background: var(--gold-pale); border-color: var(--gold); color: var(--navy);
    }

    @media (max-width: 767px) {
        .hide-mobile { display: none; }
        .search-wrap { min-width: 100%; }
    }
</style>

<!-- PAGE HEADER -->
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 style="font-family:'Sora',sans-serif; font-weight:700; color:var(--navy); margin:0; font-size:20px;">
            Employees
        </h4>
        <p style="font-size:13px; color:var(--text-muted); margin:4px 0 0;">
            All staff registered across the system
        </p>
    </div>
    <div style="font-size:12px; color:var(--text-muted); padding-top:4px;">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d M Y') }}
    </div>
</div>

<!-- STATS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="emp-stat-card">
            <div class="emp-stat-icon esi-total"><i class="bi bi-people"></i></div>
            <div>
                <div class="emp-stat-value">{{ $employees->total() }}</div>
                <div class="emp-stat-label">Total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="emp-stat-card">
            <div class="emp-stat-icon esi-male"><i class="bi bi-person"></i></div>
            <div>
                <div class="emp-stat-value" style="color:#1d4ed8;">
                    {{ $employees->getCollection()->where('gender','Male')->count() }}
                </div>
                <div class="emp-stat-label">Male</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="emp-stat-card">
            <div class="emp-stat-icon esi-female"><i class="bi bi-person-fill"></i></div>
            <div>
                <div class="emp-stat-value" style="color:#be185d;">
                    {{ $employees->getCollection()->where('gender','Female')->count() }}
                </div>
                <div class="emp-stat-label">Female</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="emp-stat-card">
            <div class="emp-stat-icon esi-active"><i class="bi bi-briefcase-fill"></i></div>
            <div>
                <div class="emp-stat-value" style="color:#059669;">
                    {{ $employees->getCollection()->filter(fn($e) => $e->employmentRecords->where('employment_status','active')->count() > 0)->count() }}
                </div>
                <div class="emp-stat-label">Employed</div>
            </div>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="filters-bar mb-4">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-inp" id="empSearch"
               placeholder="Search by name, NID, email, district…">
    </div>
    <select class="filter-sel" id="genderFilter">
        <option value="">All Genders</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
    <select class="filter-sel" id="districtFilter">
        <option value="">All Districts</option>
        @foreach($employees->getCollection()->pluck('district')->filter()->unique()->sort() as $district)
        <option value="{{ strtolower($district) }}">{{ $district }}</option>
        @endforeach
    </select>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- TABLE CARD -->
<div class="table-card">
    <div class="table-card-header">
        <h6><i class="bi bi-people me-2"></i>Employee Registry</h6>
        <span style="font-size:12px; color:var(--text-muted);">
            {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }}
        </span>
    </div>

    @if($employees->count())

    <div style="overflow-x:auto;">
        <table class="table emp-table mb-0" id="empTable">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Employee</th>
                    <th class="hide-mobile">NID</th>
                    <th class="hide-mobile">Gender</th>
                    <th class="hide-mobile">Contact</th>
                    <th class="hide-mobile">Location</th>
                    <th>Current Position</th>
                    <th class="hide-mobile">Employer</th>
                    <th style="width:100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                @php
                    $activeRecord = $employee->employmentRecords
                        ->where('employment_status', 'active')->first()
                        ?? $employee->employmentRecords->first();
                @endphp
                <tr data-name="{{ strtolower($employee->full_name) }}"
                    data-nid="{{ strtolower($employee->nid) }}"
                    data-email="{{ strtolower($employee->email) }}"
                    data-district="{{ strtolower($employee->district) }}"
                    data-gender="{{ strtolower($employee->gender) }}">

                    <td style="color:var(--text-muted); font-size:12px;">
                        {{ $employees->firstItem() + $loop->index }}
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($employee->photo)
                                <div class="emp-avatar">
                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                         alt="{{ $employee->full_name }}">
                                </div>
                            @else
                                <div class="emp-avatar {{ $employee->gender === 'Female' ? 'emp-avatar-female' : 'emp-avatar-male' }}">
                                    {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600; color:#1a2e45; font-size:14px; white-space:nowrap;">
                                    {{ $employee->full_name }}
                                </div>
                                <div style="font-size:11px; color:var(--text-muted);">
                                    DOB: {{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="hide-mobile">
                        <span class="nid-code">{{ $employee->nid }}</span>
                    </td>

                    <td class="hide-mobile">
                        <span class="gender-pill {{ $employee->gender === 'Female' ? 'gender-female' : 'gender-male' }}">
                            <i class="bi bi-{{ $employee->gender === 'Female' ? 'gender-female' : 'gender-male' }}"></i>
                            {{ $employee->gender }}
                        </span>
                    </td>

                    <td class="hide-mobile">
                        <div style="font-size:13px; color:#1a2e45;">{{ $employee->email ?? '—' }}</div>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                            <i class="bi bi-telephone me-1"></i>{{ $employee->phone ?? '—' }}
                        </div>
                    </td>

                    <td class="hide-mobile">
                        <div style="font-size:13px; color:#1a2e45; font-weight:500;">
                            {{ $employee->district ?? '—' }}
                        </div>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                            {{ $employee->sector ?? '' }}
                        </div>
                    </td>

                    <td>
                        @if($activeRecord)
                        <div style="font-size:13px; font-weight:600; color:#1a2e45;">
                            {{ $activeRecord->job_title }}
                        </div>
                        <div style="margin-top:3px;">
                            @php $st = $activeRecord->employment_status; @endphp
                            <span class="status-pill sp-{{ $st }}">
                                {{ ucfirst($st) }}
                            </span>
                        </div>
                        @else
                        <span style="font-size:13px; color:var(--text-muted);">—</span>
                        @endif
                    </td>

                    <td class="hide-mobile">
                        @if($activeRecord && $activeRecord->employer)
                        <div style="font-size:13px; color:#1a2e45; font-weight:500; white-space:nowrap;">
                            {{ $activeRecord->employer->company_name }}
                        </div>
                        @else
                        <span style="font-size:13px; color:var(--text-muted);">—</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('government.employees.show', $employee) }}" class="btn-view">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex align-items-center justify-content-between px-4 py-3"
         style="border-top:1px solid var(--border); background:#fafbfc;">
        <span style="font-size:12px; color:var(--text-muted);">
            Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }} employees
        </span>
        <div>{{ $employees->links() }}</div>
    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-people"></i></div>
        <h6 style="font-family:'Sora',sans-serif; font-weight:600; color:var(--navy); margin-bottom:6px;">
            No employees found
        </h6>
        <p style="font-size:13px; color:var(--text-muted); margin:0;">
            No employees are registered in the system yet.
        </p>
    </div>
    @endif
</div>

<script>
    const empSearch     = document.getElementById('empSearch');
    const genderFilter  = document.getElementById('genderFilter');
    const districtFilter= document.getElementById('districtFilter');
    const empRows       = document.querySelectorAll('#empTable tbody tr');

    function filterEmployees() {
        const q  = empSearch.value.toLowerCase().trim();
        const g  = genderFilter.value.toLowerCase();
        const d  = districtFilter.value.toLowerCase();
        empRows.forEach(row => {
            const matchQ = !q || row.dataset.name.includes(q) || row.dataset.nid.includes(q)
                              || row.dataset.email.includes(q) || row.dataset.district.includes(q);
            const matchG = !g || row.dataset.gender === g;
            const matchD = !d || row.dataset.district === d;
            row.style.display = (matchQ && matchG && matchD) ? '' : 'none';
        });
    }

    empSearch.addEventListener('input', filterEmployees);
    genderFilter.addEventListener('change', filterEmployees);
    districtFilter.addEventListener('change', filterEmployees);
</script>

@endsection