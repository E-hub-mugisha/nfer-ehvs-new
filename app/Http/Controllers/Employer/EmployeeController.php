<?php
// app/Http/Controllers/Employer/EmployeeController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
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
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'\-]+$/u'],
            'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'\-]+$/u'],
            'nid'        => ['required', 'string', 'regex:/^[0-9]{16}$/', 'unique:employees,nid,' . $employee->id],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'dob'        => ['required', 'date', 'before:' . now()->subYears(16)->toDateString()],
            'phone'      => ['nullable', 'string', 'regex:/^(\+?250|0)?7[0-9]{8}$/'],
            'email'      => ['nullable', 'email', 'max:255'],
            'district'   => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'sector'     => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ], [
            'first_name.regex' => 'First name may only contain letters (no numbers).',
            'last_name.regex'  => 'Last name may only contain letters (no numbers).',
            'nid.regex'        => 'NID must be exactly 16 digits.',
            'dob.before'       => 'Employee must be at least 16 years old.',
            'phone.regex'      => 'Please enter a valid Rwandan phone number (e.g. 078XXXXXXX or +2507XXXXXXXX).',
            'district.regex'   => 'District may only contain letters.',
            'sector.regex'     => 'Sector may only contain letters.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return back()->with('success', 'Employee profile updated.');
    }

    public function destroy(Employee $employee)
    {
        $employerId = Auth::user()->employer->id;

        $isLinked = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->exists();

        abort_unless($isLinked, 403, 'You do not have permission to delete this employee.');

        $records = $employee->employmentRecords()
            ->where('employer_id', $employerId)
            ->get();

        foreach ($records as $record) {
            TransferRequest::where('current_employment_record_id', $record->id)
                ->delete();

            $record->delete();
        }

        $employee->delete();

        return redirect()
            ->route('employer.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}