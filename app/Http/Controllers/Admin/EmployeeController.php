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
use Illuminate\Validation\Rule;

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
     * Normalize raw input before validation (trim, strip spaces, lowercase email).
     */
    private function normalizeEmployeeInput(Request $request): void
    {
        $request->merge([
            'nid'        => preg_replace('/\s+/', '', (string) $request->nid),
            'phone'      => $request->phone ? preg_replace('/[\s\-]/', '', $request->phone) : null,
            'email'      => $request->email ? strtolower(trim($request->email)) : null,
            'first_name' => $request->first_name ? trim($request->first_name) : null,
            'last_name'  => $request->last_name ? trim($request->last_name) : null,
        ]);
    }

    private function employeeMessages(): array
    {
        return [
            'nid.digits'       => 'The National ID must be exactly 16 digits.',
            'nid.unique'       => 'An employee with this National ID already exists.',
            'first_name.regex' => 'First name may only contain letters, spaces, and hyphens.',
            'last_name.regex'  => 'Last name may only contain letters, spaces, and hyphens.',
            'dob.before'       => 'Employee must be at least 16 years old.',
            'dob.after'        => 'Please enter a valid date of birth.',
            'phone.regex'      => 'Enter a valid Rwandan phone number (e.g. 078XXXXXXX or +2507XXXXXXXX).',
            'photo.dimensions' => 'Photo must be at least 200x200 pixels.',
        ];
    }

    /**
     * Store a newly created employee + linked user account.
     */
    public function store(Request $request)
    {
        $this->normalizeEmployeeInput($request);

        $validated = $request->validate([
            'nid'        => ['required', 'digits:16', 'unique:employees,nid'],
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
            'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
            'gender'     => ['required', Rule::in(['Male', 'Female'])],
            'dob'        => ['required', 'date', 'before:-16 years', 'after:-100 years'],
            'phone'      => ['nullable', 'regex:/^(\+?250|0)7[2-9][0-9]{7}$/'],
            'email'      => [
                'nullable', 'email:rfc,dns', 'max:255',
                'unique:employees,email', 'unique:users,email',
            ],
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=200,min_height=200'],
            'district'   => ['required', 'string', 'max:100'],
            'sector'     => ['required', 'string', 'max:100'],
        ], $this->employeeMessages());

        $employee = DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            // Optionally create a linked login account if an email was provided
            if (!empty($validated['email'])) {
                $user = User::create([
                    'name'     => "{$validated['first_name']} {$validated['last_name']}",
                    'email'    => $validated['email'],
                    'password' => Hash::make(Str::password(12)),
                    'role'     => 'employee',
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
        $this->normalizeEmployeeInput($request);

        $validated = $request->validate([
            'nid'        => ['required', 'digits:16', Rule::unique('employees', 'nid')->ignore($employee->id)],
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
            'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
            'gender'     => ['required', Rule::in(['Male', 'Female'])],
            'dob'        => ['required', 'date', 'before:-16 years', 'after:-100 years'],
            'phone'      => ['nullable', 'regex:/^(\+?250|0)7[2-9][0-9]{7}$/'],
            'email'      => [
                'nullable', 'email:rfc,dns', 'max:255',
                Rule::unique('employees', 'email')->ignore($employee->id),
            ],
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=200,min_height=200'],
            'district'   => ['required', 'string', 'max:100'],
            'sector'     => ['required', 'string', 'max:100'],
        ], $this->employeeMessages());

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
        DB::transaction(function () use ($employee) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            foreach ($employee->employmentRecords as $record) {
                $record->transferRequests()->delete();
                $record->delete();
            }

            $employee->delete();
        });

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}