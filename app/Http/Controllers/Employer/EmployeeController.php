<?php
// app/Http/Controllers/Employer/EmployeeController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmploymentRecord;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display all employees linked to the authenticated employer.
     */
    public function index()
    {
        $employerId = Auth::id();

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
        $employerId = Auth::id();

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
}