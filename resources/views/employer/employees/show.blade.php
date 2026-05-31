@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' — Profile')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Back link --}}
    <a href="{{ route('employer.employees.index') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i> Back to Employees
    </a>

    <div class="row g-4">

        {{-- LEFT: Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">

                {{-- Avatar --}}
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}"
                         alt="{{ $employee->first_name }}"
                         class="rounded-circle mx-auto object-fit-cover mb-3 border border-3 border-primary"
                         width="110" height="110">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary
                                d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
                         style="width:110px;height:110px;font-size:36px;">
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                <p class="text-muted small mb-3">{{ $employee->email ?? 'No email on record' }}</p>

                <span class="badge
                    {{ $employee->gender === 'Male' ? 'bg-info bg-opacity-10 text-info' : 'bg-danger bg-opacity-10 text-danger' }}
                    rounded-pill px-3 py-2">
                    {{ $employee->gender }}
                </span>

                <div class="mt-3">
                    <button type="button"
                            class="btn btn-sm btn-outline-primary w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#editEmployeeModal">
                        <i class="bi bi-person-gear me-1"></i> Edit Profile
                    </button>
                </div>

                <hr class="my-4">

                {{-- Personal Details --}}
                <ul class="list-unstyled text-start small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">NID</span>
                        <strong><code>{{ $employee->nid }}</code></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Date of Birth</span>
                        <strong>{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Age</span>
                        <strong>{{ \Carbon\Carbon::parse($employee->dob)->age }} yrs</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Phone</span>
                        <strong>{{ $employee->phone ?? '—' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">District</span>
                        <strong>{{ $employee->district ?? '—' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Sector</span>
                        <strong>{{ $employee->sector ?? '—' }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        {{-- RIGHT: Employment Records --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-briefcase me-2 text-primary"></i>
                        Employment History
                        <span class="badge bg-primary ms-2">{{ $employmentRecords->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($employmentRecords->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No employment records found.
                        </div>
                    @else
                        <div class="timeline p-4">
                            @foreach($employmentRecords as $record)
                            <div class="d-flex gap-3 mb-4">

                                {{-- Timeline dot --}}
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle bg-primary"
                                         style="width:12px;height:12px;margin-top:4px;flex-shrink:0;"></div>
                                    @if(!$loop->last)
                                        <div class="bg-primary bg-opacity-25 flex-grow-1"
                                             style="width:2px;margin-top:4px;"></div>
                                    @endif
                                </div>

                                {{-- Record Card --}}
                                <div class="card border shadow-none w-100 mb-0">
                                    <div class="card-body py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <h6 class="fw-semibold mb-0">
                                                    {{ $record->job_title ?? 'Unknown job title' }}
                                                </h6>
                                                <small class="text-muted">{{ $record->department ?? '' }}</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($record->end_date === null)
                                                    <span class="badge bg-success">Current</span>
                                                @else
                                                    <span class="badge bg-secondary">Ended</span>
                                                @endif
                                                {{-- Edit Button --}}
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary py-0 px-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRecordModal"
                                                        data-record-id="{{ $record->id }}"
                                                        data-job-title="{{ $record->job_title }}"
                                                        data-department="{{ $record->department }}"
                                                        data-start-date="{{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('Y-m-d') : '' }}"
                                                        data-end-date="{{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('Y-m-d') : '' }}"
                                                        data-salary="{{ $record->salary ?? '' }}"
                                                        data-contract-type="{{ $record->contract_type ?? '' }}"
                                                        data-notes="{{ $record->notes ?? '' }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <hr class="my-2">

                                        <div class="row g-2 small text-muted">
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                <strong>Start:</strong>
                                                {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('d M Y') : '—' }}
                                            </div>
                                            <div class="col-sm-6">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                <strong>End:</strong>
                                                {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('d M Y') : 'Present' }}
                                            </div>
                                            @if(isset($record->salary))
                                            <div class="col-sm-6">
                                                <i class="bi bi-cash me-1"></i>
                                                <strong>Salary:</strong>
                                                RWF {{ number_format($record->salary) }}
                                            </div>
                                            @endif
                                            @if(isset($record->contract_type))
                                            <div class="col-sm-6">
                                                <i class="bi bi-file-text me-1"></i>
                                                <strong>Contract:</strong>
                                                {{ $record->contract_type }}
                                            </div>
                                            @endif
                                        </div>

                                        @if(!empty($record->notes))
                                            <div class="mt-2 p-2 bg-light rounded small text-muted">
                                                <i class="bi bi-sticky me-1"></i> {{ $record->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ============================================================
     Edit Employment Record Modal
     ============================================================ --}}
<div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom px-4 py-3">
                <h6 class="modal-title fw-bold" id="editRecordModalLabel">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Employment Record
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editRecordForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-body px-4 py-4">

                    @if($errors->any())
                        <div class="alert alert-danger small py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">

                        {{-- Job Title --}}
                        <div class="col-sm-6">
                            <label for="edit_job_title" class="form-label fw-semibold small">
                                Job Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="edit_job_title"
                                   name="job_title"
                                   class="form-control @error('job_title') is-invalid @enderror"
                                   placeholder="e.g. Software Engineer"
                                   required>
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-sm-6">
                            <label for="edit_department" class="form-label fw-semibold small">Department</label>
                            <input type="text"
                                   id="edit_department"
                                   name="department"
                                   class="form-control @error('department') is-invalid @enderror"
                                   placeholder="e.g. Engineering">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="col-sm-6">
                            <label for="edit_start_date" class="form-label fw-semibold small">
                                Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="edit_start_date"
                                   name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- End Date --}}
                        <div class="col-sm-6">
                            <label for="edit_end_date" class="form-label fw-semibold small">
                                End Date
                                <span class="text-muted fw-normal">(leave blank if current)</span>
                            </label>
                            <input type="date"
                                   id="edit_end_date"
                                   name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Salary --}}
                        <div class="col-sm-6">
                            <label for="edit_salary" class="form-label fw-semibold small">Salary (RWF)</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">RWF</span>
                                <input type="number"
                                       id="edit_salary"
                                       name="salary"
                                       class="form-control @error('salary') is-invalid @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="1">
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Contract Type --}}
                        <div class="col-sm-6">
                            <label for="edit_contract_type" class="form-label fw-semibold small">Contract Type</label>
                            <select id="edit_contract_type"
                                    name="contract_type"
                                    class="form-select @error('contract_type') is-invalid @enderror">
                                <option value="">— Select —</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                                <option value="Casual">Casual</option>
                            </select>
                            @error('contract_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label for="edit_notes" class="form-label fw-semibold small">Notes</label>
                            <textarea id="edit_notes"
                                      name="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Any additional notes…"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 py-3 border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     Edit Employee Profile Modal
     ============================================================ --}}
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom px-4 py-3">
                <h6 class="modal-title fw-bold" id="editEmployeeModalLabel">
                    <i class="bi bi-person-gear me-2 text-primary"></i>Edit Employee Profile
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST"
                  action="{{ route('employer.employees.update', $employee->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body px-4 py-4">

                    @if($errors->any())
                        <div class="alert alert-danger small py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Photo upload --}}
                    <div class="d-flex align-items-center gap-4 mb-4 p-3 bg-light rounded">
                        <div id="emp_avatar_preview">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                     id="emp_photo_img"
                                     class="rounded-circle object-fit-cover border border-2 border-primary"
                                     width="72" height="72" alt="Photo">
                            @else
                                <div id="emp_photo_initials"
                                     class="rounded-circle bg-primary bg-opacity-10 text-primary
                                            d-flex align-items-center justify-content-center fw-bold"
                                     style="width:72px;height:72px;font-size:24px;">
                                    {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <label for="emp_photo" class="form-label fw-semibold small mb-1">Profile Photo</label>
                            <input type="file"
                                   id="emp_photo"
                                   name="photo"
                                   accept="image/*"
                                   class="form-control form-control-sm @error('photo') is-invalid @enderror">
                            <div class="form-text">JPG, PNG or WEBP · max 2 MB</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- First Name --}}
                        <div class="col-sm-6">
                            <label for="emp_first_name" class="form-label fw-semibold small">
                                First Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="emp_first_name"
                                   name="first_name"
                                   value="{{ old('first_name', $employee->first_name) }}"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="col-sm-6">
                            <label for="emp_last_name" class="form-label fw-semibold small">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="emp_last_name"
                                   name="last_name"
                                   value="{{ old('last_name', $employee->last_name) }}"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NID --}}
                        <div class="col-sm-6">
                            <label for="emp_nid" class="form-label fw-semibold small">
                                NID <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="emp_nid"
                                   name="nid"
                                   value="{{ old('nid', $employee->nid) }}"
                                   class="form-control @error('nid') is-invalid @enderror"
                                   required>
                            @error('nid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-sm-6">
                            <label for="emp_gender" class="form-label fw-semibold small">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <select id="emp_gender"
                                    name="gender"
                                    class="form-select @error('gender') is-invalid @enderror"
                                    required>
                                <option value="">— Select —</option>
                                <option value="Male"   {{ old('gender', $employee->gender) === 'Male'   ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $employee->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other"  {{ old('gender', $employee->gender) === 'Other'  ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-sm-6">
                            <label for="emp_dob" class="form-label fw-semibold small">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   id="emp_dob"
                                   name="dob"
                                   value="{{ old('dob', $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('Y-m-d') : '') }}"
                                   class="form-control @error('dob') is-invalid @enderror"
                                   required>
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-sm-6">
                            <label for="emp_phone" class="form-label fw-semibold small">Phone</label>
                            <input type="text"
                                   id="emp_phone"
                                   name="phone"
                                   value="{{ old('phone', $employee->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="+250 7XX XXX XXX">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-sm-6">
                            <label for="emp_email" class="form-label fw-semibold small">Email</label>
                            <input type="email"
                                   id="emp_email"
                                   name="email"
                                   value="{{ old('email', $employee->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="employee@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- District --}}
                        <div class="col-sm-6">
                            <label for="emp_district" class="form-label fw-semibold small">District</label>
                            <input type="text"
                                   id="emp_district"
                                   name="district"
                                   value="{{ old('district', $employee->district) }}"
                                   class="form-control @error('district') is-invalid @enderror"
                                   placeholder="e.g. Kigali">
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sector --}}
                        <div class="col-sm-6">
                            <label for="emp_sector" class="form-label fw-semibold small">Sector</label>
                            <input type="text"
                                   id="emp_sector"
                                   name="sector"
                                   value="{{ old('sector', $employee->sector) }}"
                                   class="form-control @error('sector') is-invalid @enderror"
                                   placeholder="e.g. Nyarugenge">
                            @error('sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 py-3 border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Employment Record modal: populate fields on open ──────────────────
    const recordModal  = document.getElementById('editRecordModal');
    const recordForm   = document.getElementById('editRecordForm');
    const baseAction   = "{{ url('employer/get/employment-records') }}";

    recordModal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;

        const id           = btn.dataset.recordId;
        const jobTitle     = btn.dataset.jobTitle     ?? '';
        const department   = btn.dataset.department   ?? '';
        const startDate    = btn.dataset.startDate    ?? '';
        const endDate      = btn.dataset.endDate      ?? '';
        const salary       = btn.dataset.salary       ?? '';
        const contractType = btn.dataset.contractType ?? '';
        const notes        = btn.dataset.notes        ?? '';

        recordForm.action = `${baseAction}/${id}`;

        document.getElementById('edit_job_title').value  = jobTitle;
        document.getElementById('edit_department').value = department;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value   = endDate;
        document.getElementById('edit_salary').value     = salary;
        document.getElementById('edit_notes').value      = notes;

        const contractSelect = document.getElementById('edit_contract_type');
        contractSelect.value = contractType;
        if (!contractSelect.value) contractSelect.value = '';
    });

    // ── Employee Profile modal: live photo preview ────────────────────────
    const photoInput = document.getElementById('emp_photo');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('emp_avatar_preview');

                // Replace whatever is inside the preview container with a fresh <img>
                preview.innerHTML =
                    `<img src="${e.target.result}"
                          class="rounded-circle object-fit-cover border border-2 border-primary"
                          width="72" height="72" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Re-open Employee modal automatically if validation failed ─────────
    // Laravel redirects back with errors; detect them and reopen the modal.
    @if($errors->any())
        const empModalEl = document.getElementById('editEmployeeModal');
        if (empModalEl) {
            const empModal = new bootstrap.Modal(empModalEl);
            empModal.show();
        }
    @endif

});
</script>
@endsection