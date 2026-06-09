<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploymentRecordController extends Controller
{
    public function index()
    {
        $employerId = Employer::where('user_id', Auth::user()->id)->value('id');

        $employees = Employee::whereHas('employmentRecords', function ($query) use ($employerId) {
            $query->where('employer_id', $employerId);
        })
            ->with(['employmentRecords' => function ($query) use ($employerId) {
                $query->where('employer_id', $employerId)->latest();
            }])
            ->orderBy('first_name')
            ->paginate(15);

        return view('employer.employees.records.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        $employerId = Employer::where('user_id', Auth::user()->id)->value('id');

        $isLinked = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->exists();

        abort_unless($isLinked, 403, 'You do not have access to this employee profile.');

        $employmentRecords = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->latest()
            ->get();

        return view('employer.employees.records.show', compact('employee', 'employmentRecords'));
    }

    // EmploymentRecordController.php
    public function update(Request $request, EmploymentRecord $record)
    {
        $validated = $request->validate([
            'job_title'     => 'required|string|max:255',
            'department'    => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'salary'        => 'nullable|numeric|min:0',
            'contract_type' => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
        ]);

        $record->update($validated);

        return back()->with('success', 'Employment record updated.');
    }

    public function destroy(EmploymentRecord $record)
    {
        $record->delete();

        return back()->with('success', 'Employment record deleted.');
    }
}
