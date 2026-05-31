<?php
// app/Http/Controllers/Employer/EmployeeController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $employerId = Employer::where('user_id', Auth::id())->value('id'); // ← employers.id, not users.id

        $employees = Employee::whereHas('employmentRecords', function ($query) use ($employerId) {
            $query->where('employer_id', $employerId);
        })
            ->with(['employmentRecords' => function ($query) use ($employerId) {
                $query->where('employer_id', $employerId)
                    ->latest();
            }])
            ->orderBy('first_name')
            ->paginate(15);

        return view('employer.employees.index', compact('employees'));
    }

    /**
     * Display a single employee's profile details.
     * Only accessible if the employee belongs to this employer.
     */
    public function show(Employee $employee)
    {
        $employerId = Auth::user()->employer->id;

        // Ensure this employee is linked to the authenticated employer
        $isLinked = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->exists();

        abort_unless($isLinked, 403, 'You do not have access to this employee profile.');

        $employmentRecords = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->latest()
            ->get();

        return view('employer.employees.show', compact('employee', 'employmentRecords'));
    }

    // EmployeeController.php
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'nid'        => 'required|string|unique:employees,nid,' . $employee->id,
            'gender'     => 'required|in:Male,Female,Other',
            'dob'        => 'required|date|before:today',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'district'   => 'nullable|string|max:100',
            'sector'     => 'nullable|string|max:100',
            'photo'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return back()->with('success', 'Employee profile updated.');
    }
}
