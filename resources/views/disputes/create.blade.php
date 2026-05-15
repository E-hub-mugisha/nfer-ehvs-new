{{-- resources/views/disputes/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Raise Dispute')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-1">
                        Raise Employment Dispute
                    </h4>

                    <p class="text-muted mb-0">
                        Submit your complaint or issue regarding employment.
                    </p>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('disputes.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        {{-- Employment Record --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Employment Record
                            </label>

                            <select name="employment_record_id"
                                    class="form-select rounded-3 @error('employment_record_id') is-invalid @enderror">

                                <option value="">
                                    Select Employment Record
                                </option>

                                @foreach($records as $record)
                                    <option value="{{ $record->id }}">
                                        {{ $record->employer->company_name ?? 'Employer' }}
                                        - {{ $record->job_title }}
                                    </option>
                                @endforeach

                            </select>

                            @error('employment_record_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Dispute Description
                            </label>

                            <textarea name="description"
                                      rows="6"
                                      class="form-control rounded-3 @error('description') is-invalid @enderror"
                                      placeholder="Describe your dispute in detail...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Evidence --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Upload Evidence (Optional)
                            </label>

                            <input type="file"
                                   name="evidence"
                                   class="form-control rounded-3 @error('evidence') is-invalid @enderror">

                            <small class="text-muted">
                                Supported: PDF, JPG, PNG, DOCX
                            </small>

                            @error('evidence')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                    class="btn btn-primary rounded-pill px-4">
                                Submit Dispute
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection