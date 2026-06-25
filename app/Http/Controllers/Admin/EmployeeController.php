<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('employmentRecords')
            ->latest()
            ->paginate(15);

        return view('admin.employees.index', compact('employees'));
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

        return view('admin.employees.show', compact('employee', 'employmentRecords'));
    }

    public function destroy(Employee $employee)
    {
        // Cascade deletes will handle related records if FK constraints are set,
        // otherwise manually clean up first:
        $employee->disputes()->delete();
        $employee->transferRequests()->delete();
        $employee->employmentRecords()->delete();

        // Delete photo from storage if present
        if ($employee->photo) {
            Storage::delete('public/' . $employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('government.employees.index')
            ->with('success', "Employee \"{$employee->full_name}\" has been deleted.");
    }
}
