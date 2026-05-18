@extends('layouts.app')
@section('title', 'Search Employee')

@section('content')
<div class="container py-5" style="max-width:640px;">

    <div class="text-center mb-5">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
             style="width:64px;height:64px;">
            <i class="bi bi-search text-primary fs-3"></i>
        </div>
        <h4 class="fw-bold">Search Employee</h4>
        <p class="text-muted">Search by National ID (NID) or full name.<br>
           If the employee isn't registered, you'll be guided to create their profile.</p>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning d-flex gap-2 align-items-center">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->has('query'))
        <div class="alert alert-danger">{{ $errors->first('query') }}</div>
    @endif

    <div class="card border-0 shadow-sm p-4">
        <form action="{{ route('employer.search.query') }}" method="POST">
            @csrf
            <label class="form-label fw-semibold">NID or Employee Name</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white">
                    <i class="bi bi-person-badge text-muted"></i>
                </span>
                <input type="text"
                       name="query"
                       value="{{ old('query') }}"
                       class="form-control border-start-0 ps-0 @error('query') is-invalid @enderror"
                       placeholder="e.g. 1199280012345678 or John Doe"
                       autofocus>
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="bi bi-info-circle me-1"></i>
                NID search is exact match. Name search supports partial matching.
            </small>
        </form>
    </div>

</div>
@endsection