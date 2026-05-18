@extends('layouts.app')
@section('title', 'Register New Employee')

@section('content')
<div class="container py-4" style="max-width:820px;">

    {{-- Header --}}
    <div class="mb-4">
        <a href="{{ route('employer.search.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Search
        </a>
        <div class="alert alert-warning d-flex gap-2 align-items-start">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>
                <strong>Employee not found in the system.</strong><br>
                Fill in the details below to register this employee. A user account will be
                automatically created and login credentials sent to their email address.
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employer.search.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ── SECTION 1: Personal Information ── --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 px-4">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person me-2"></i>Personal Information
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- Photo --}}
                    <div class="col-12 d-flex align-items-center gap-4 mb-2">
                        <div id="photoPreviewWrap">
                            <div id="photoPlaceholder"
                                 class="rounded-circle bg-light border d-flex align-items-center
                                        justify-content-center text-muted"
                                 style="width:90px;height:90px;">
                                <i class="bi bi-person fs-1"></i>
                            </div>
                            <img id="photoPreview" src="#" alt="Preview"
                                 class="rounded-circle d-none object-fit-cover border"
                                 style="width:90px;height:90px;">
                        </div>
                        <div>
                            <label class="form-label fw-semibold d-block mb-1">Profile Photo</label>
                            <input type="file" name="photo" id="photoInput"
                                   class="form-control form-control-sm @error('photo') is-invalid @enderror"
                                   accept="image/jpg,image/jpeg,image/png"
                                   style="max-width:260px;">
                            <small class="text-muted">JPG/PNG, max 2MB (optional)</small>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            National ID (NID) <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nid"
                               value="{{ old('nid', $prefillNid) }}"
                               class="form-control @error('nid') is-invalid @enderror"
                               placeholder="e.g. 1199280012345678">
                        @error('nid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            First Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror"
                               placeholder="John">
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Last Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror"
                               placeholder="Doe">
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Gender <span class="text-danger">*</span>
                        </label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select gender...</option>
                            <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Date of Birth <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="dob"
                               value="{{ old('dob') }}"
                               max="{{ date('Y-m-d') }}"
                               class="form-control @error('dob') is-invalid @enderror">
                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Phone <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="phone"
                               value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="+250 7XX XXX XXX">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="john.doe@example.com">
                        <small class="text-muted">Login credentials will be sent here.</small>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            District <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="district"
                               value="{{ old('district') }}"
                               class="form-control @error('district') is-invalid @enderror"
                               placeholder="Kigali">
                        @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Sector <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="sector"
                               value="{{ old('sector') }}"
                               class="form-control @error('sector') is-invalid @enderror"
                               placeholder="Nyarugenge">
                        @error('sector')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── SECTION 2: Initial Employment Record ── --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3 px-4">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-briefcase me-2"></i>Employment Details (at your organization)
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Job Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="job_title"
                               value="{{ old('job_title') }}"
                               class="form-control @error('job_title') is-invalid @enderror"
                               placeholder="e.g. Software Engineer">
                        @error('job_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Department</label>
                        <input type="text" name="department"
                               value="{{ old('department') }}"
                               class="form-control @error('department') is-invalid @enderror"
                               placeholder="e.g. Engineering">
                        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Employment Type <span class="text-danger">*</span>
                        </label>
                        <select name="employment_type"
                                class="form-select @error('employment_type') is-invalid @enderror">
                            <option value="">Select...</option>
                            @foreach(['active','resigned','terminated'] as $type)
                                <option value="{{ $type }}" {{ old('employment_type') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Start Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', date('Y-m-d')) }}"
                               class="form-control @error('start_date') is-invalid @enderror">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date') }}"
                               class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Auto-Credentials Notice ── --}}
        <div class="alert alert-info d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-envelope-check-fill fs-5 flex-shrink-0"></i>
            <div>
                <strong>Automatic Account Creation:</strong> A system account will be created for this
                employee using the email above. A temporary password will be emailed to them upon submission.
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('employer.search.index') }}" class="btn btn-outline-secondary px-4">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary px-5">
                <i class="bi bi-person-plus me-2"></i>Register Employee & Send Credentials
            </button>
        </div>

    </form>
</div>

<script>
    // Photo preview
    document.getElementById('photoInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreview').classList.remove('d-none');
            document.getElementById('photoPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection