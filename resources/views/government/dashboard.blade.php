@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                Government Dashboard
            </h2>

            <p class="text-muted mb-0">
                Employment Monitoring & Verification System
            </p>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">

        {{-- Employees --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Employees
                            </p>

                            <h3 class="fw-bold">
                                {{ number_format($employees) }}
                            </h3>
                        </div>

                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px; height:60px;">

                            <i class="fas fa-users fa-lg"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- Employers --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Employers
                            </p>

                            <h3 class="fw-bold">
                                {{ number_format($employers) }}
                            </h3>
                        </div>

                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px; height:60px;">

                            <i class="fas fa-building fa-lg"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- Verified Employers --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Verified Employers
                            </p>

                            <h3 class="fw-bold">
                                {{ number_format($verifiedEmployers) }}
                            </h3>
                        </div>

                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px; height:60px;">

                            <i class="fas fa-check-circle fa-lg"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- Employment Records --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Employment Records
                            </p>

                            <h3 class="fw-bold">
                                {{ number_format($records) }}
                            </h3>
                        </div>

                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px; height:60px;">

                            <i class="fas fa-briefcase fa-lg"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Second Row --}}
    <div class="row g-4">

        {{-- Disputes --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h5 class="fw-bold">
                                Total Disputes
                            </h5>

                            <h2 class="text-danger fw-bold">
                                {{ number_format($disputes) }}
                            </h2>
                        </div>

                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:70px; height:70px;">

                            <i class="fas fa-exclamation-triangle fa-2x"></i>

                        </div>

                    </div>

                    <p class="text-muted">
                        Track all employment-related disputes submitted by employees.
                    </p>

                    <a href="#"
                       class="btn btn-outline-danger btn-sm">
                        View Disputes
                    </a>

                </div>

            </div>
        </div>

        {{-- Verification Status --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Employer Verification Status
                    </h5>

                    <div class="mb-3">

                        <div class="d-flex justify-content-between mb-1">
                            <span>Verified</span>
                            <span>{{ $verifiedEmployers }}</span>
                        </div>

                        <div class="progress" style="height:10px;">
                            <div class="progress-bar bg-success"
                                 style="width:
                                 {{ $employers > 0 ? ($verifiedEmployers / $employers) * 100 : 0 }}%">
                            </div>
                        </div>

                    </div>

                    <div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Pending</span>
                            <span>{{ $employers - $verifiedEmployers }}</span>
                        </div>

                        <div class="progress" style="height:10px;">
                            <div class="progress-bar bg-warning"
                                 style="width:
                                 {{ $employers > 0 ? (($employers - $verifiedEmployers) / $employers) * 100 : 0 }}%">
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Quick Actions
                    </h5>

                    <div class="d-grid gap-3">

                        <a href="#"
                           class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>
                            Manage Employees
                        </a>

                        <a href="#"
                           class="btn btn-success">
                            <i class="fas fa-building me-2"></i>
                            Verify Employers
                        </a>

                        <a href="#"
                           class="btn btn-warning text-white">
                            <i class="fas fa-briefcase me-2"></i>
                            Employment Records
                        </a>

                        <a href="#"
                           class="btn btn-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Resolve Disputes
                        </a>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection