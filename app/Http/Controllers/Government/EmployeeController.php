<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('employmentRecords')
            ->latest()
            ->paginate(15);

        return view('government.employees.index', compact('employees'));
    }

    /**
     * Display a single employee's profile details.
     * Only accessible if the employee belongs to this employer.
     */
    public function show(Employee $employee)
    {
        $employmentRecords = $employee->employmentRecords()
            ->latest()
            ->get();

        return view('government.employees.show', compact('employee', 'employmentRecords'));
    }
}
