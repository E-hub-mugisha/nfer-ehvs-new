<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with(['employmentRecords.employer', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Store a newly created employee + linked user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nid'        => 'required|string|max:32|unique:employees,nid',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'gender'     => 'required|in:Male,Female',
            'dob'        => 'required|date|before:today',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255|unique:employees,email|unique:users,email',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'district'   => 'required|string|max:100',
            'sector'     => 'required|string|max:100',
        ]);

        $employee = DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            // Optionally create a linked login account if an email was provided
            if (!empty($validated['email'])) {
                $user = User::create([
                    'name'      => "{$validated['first_name']} {$validated['last_name']}",
                    'email'     => $validated['email'],
                    'password'  => Hash::make(Str::password(12)),
                    'role' => 'employee',
                ]);

               

                $validated['user_id'] = $user->id;
            }

            return Employee::create($validated);
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', "Employee {$employee->full_name} registered successfully.");
    }

    public function show(Employee $employee)
    {
        $employee->load(['employmentRecords.employer', 'employmentRecords.disputes', 'transferRequests.currentEmployer', 'transferRequests.requestingEmployer', 'disputes', 'user']);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Update the specified employee AND keep its linked user in sync.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nid'        => 'required|string|max:32|unique:employees,nid,' . $employee->id,
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'gender'     => 'required|in:Male,Female',
            'dob'        => 'required|date|before:today',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255|unique:employees,email,' . $employee->id,
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'district'   => 'required|string|max:100',
            'sector'     => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($validated, $request, $employee) {
            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            $employee->update($validated);

            // Keep linked user in sync, if one exists
            if ($employee->user) {
                $userUpdates = [
                    'name' => "{$validated['first_name']} {$validated['last_name']}",
                ];

                if (!empty($validated['email']) && $validated['email'] !== $employee->user->email) {
                    $emailTaken = User::where('email', $validated['email'])
                        ->where('id', '!=', $employee->user->id)
                        ->exists();

                    if (!$emailTaken) {
                        $userUpdates['email'] = $validated['email'];
                    }
                }

                $employee->user->update($userUpdates);
            }
        });

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}