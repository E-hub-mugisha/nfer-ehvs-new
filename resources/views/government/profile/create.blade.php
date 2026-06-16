@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Complete Government Profile</h4>
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        Please complete your government profile before accessing the dashboard.
                    </div>

                    <form action="{{ route('government.profile.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Government Name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input
                                type="text"
                                name="country"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country') }}"
                                required>
                            @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Government Type</label>
                            <select
                                name="government_type"
                                class="form-select @error('government_type') is-invalid @enderror"
                                required>
                                <option value="">Select Type</option>
                                <option value="Ministry">Ministry</option>
                                <option value="Department">Department</option>
                                <option value="Agency">Agency</option>
                                <option value="Authority">Authority</option>
                                <option value="National">National</option>
                            </select>

                            @error('government_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Established Year</label>
                            <input
                                type="number"
                                name="established_year"
                                class="form-control"
                                value="{{ old('established_year') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input
                                type="email"
                                name="contact_email"
                                class="form-control @error('contact_email') is-invalid @enderror"
                                value="{{ old('contact_email') }}"
                                required>

                            @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Website</label>
                            <input
                                type="url"
                                name="website"
                                class="form-control"
                                value="{{ old('website') }}"
                                placeholder="https://example.gov">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save Profile
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection