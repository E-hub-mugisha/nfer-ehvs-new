@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background:#0e2039;">
            <h4 class="mb-0">My Employee Profile</h4>
        </div>

        <div class="card-body">

            <div class="row">
                {{-- Profile Photo --}}
                <div class="col-md-3 text-center">
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}"
                             class="img-fluid rounded-circle border"
                             style="width:150px;height:150px;object-fit:cover;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:150px;height:150px;">
                            No Photo
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="col-md-9">
                    <h4 style="color:#0e2039;">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </h4>

                    <p><strong>NID:</strong> {{ $employee->nid }}</p>
                    <p><strong>Gender:</strong> {{ $employee->gender }}</p>
                    <p><strong>Date of Birth:</strong> {{ $employee->dob }}</p>
                    <p><strong>Phone:</strong> {{ $employee->phone }}</p>
                    <p><strong>Email:</strong> {{ $employee->email }}</p>
                    <p><strong>District:</strong> {{ $employee->district }}</p>
                    <p><strong>Sector:</strong> {{ $employee->sector }}</p>
                </div>
            </div>

            <hr>

            {{-- Employment Records --}}
            <h5 style="color:#d4943a;">Employment History</h5>

            @if($employee->employmentRecords->count() > 0)
                <ul class="list-group">
                    @foreach($employee->employmentRecords as $record)
                        <li class="list-group-item">
                            <strong>{{ $record->position ?? 'Position' }}</strong><br>
                            {{ $record->company ?? 'Company' }}<br>
                            <small>
                                {{ $record->start_date ?? '' }} - {{ $record->end_date ?? 'Present' }}
                            </small>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No employment records found.</p>
            @endif

        </div>
    </div>

</div>
@endsection