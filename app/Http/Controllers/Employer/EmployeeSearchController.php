<?php
// app/Http/Controllers/Employer/EmployeeSearchController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Mail\EmployeeCredentialsMail;
use App\Models\Employee;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployeeSearchController extends Controller
{
    // ─────────────────────────────────────────────
    // STEP 1 — Show search form
    // ─────────────────────────────────────────────
    public function index()
    {
        return view('employer.search.index');
    }

    // ─────────────────────────────────────────────
    // STEP 2 — Handle search (by NID or name)
    // ─────────────────────────────────────────────
    public function search(Request $request)
    {
        $request->validate([
            'query' => ['required', 'string', 'min:2'],
        ], [
            'query.required' => 'Please enter an NID or employee name to search.',
            'query.min'      => 'Search term must be at least 2 characters.',
        ]);

        $term = trim($request->input('query'));

        // Search by NID (exact) or by first/last name (partial)
        $employee = Employee::where('nid', $term)
            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$term}%")
            ->orWhere('first_name', 'LIKE', "%{$term}%")
            ->orWhere('last_name',  'LIKE', "%{$term}%")
            ->first();

        if ($employee) {
            return redirect()->route('employer.search.result', $employee->id)
                ->with('search_term', $term);
        }

        // Not found — redirect to create form, carrying the search term
        return redirect()
            ->route('employer.search.create')
            ->with('prefill_nid', $term)
            ->with('warning', "No employee found for \"{$term}\". Please fill in their profile to register them.");
    }

    // ─────────────────────────────────────────────
    // STEP 3A — Show found employee profile
    // ─────────────────────────────────────────────
    public function result(Employee $employee)
    {
        $employmentRecords = $employee->employmentRecords()
            ->latest()
            ->get();

        // Check if this employer already has an active record with this employee
        $alreadyLinked = $employee->employmentRecords()
            ->where('employer_id', Auth::id())
            ->whereNull('end_date')
            ->exists();

        $currentEmployer = Auth::user()->employer;

        $employmentRecords = $employee->employmentRecords()
            ->with('employer')
            ->orderByDesc('start_date')
            ->get();

        // Is the employee already linked to THIS employer (active)?
        $alreadyLinked = $employmentRecords
            ->where('employer_id', $currentEmployer->id)
            ->whereNull('end_date')
            ->isNotEmpty();

        // Active record with a DIFFERENT employer
        $activeRecord = $employmentRecords->whereNull('end_date')->first();

        // Pending transfer request from this employer
        $pendingTransfer = TransferRequest::where('employee_id', $employee->id)
            ->where('requesting_employer_id', $currentEmployer->id)
            ->where('status', 'pending')
            ->first();


        return view('employer.search.result', compact('employee', 'employmentRecords', 'alreadyLinked', 'activeRecord', 'pendingTransfer'));
    }

    // ─────────────────────────────────────────────
    // STEP 3B — Show create form (employee not found)
    // ─────────────────────────────────────────────
    public function create()
    {
        $prefillNid = session('prefill_nid');

        return view('employer.search.create', compact('prefillNid'));
    }

    // ─────────────────────────────────────────────
    // STEP 4 — Store new employee + auto-create user
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nid'        => ['required', 'string', 'unique:employees,nid'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'gender'     => ['required', 'in:Male,Female'],
            'dob'        => ['required', 'date', 'before:today'],
            'phone'      => ['required', 'string', 'max:20'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'district'   => ['required', 'string', 'max:100'],
            'sector'     => ['required', 'string', 'max:100'],
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Initial employment record
            'position'      => ['required', 'string', 'max:150'],
            'department'    => ['nullable', 'string', 'max:150'],
            'contract_type' => ['required', 'in:Full-Time,Part-Time,Contract,Intern'],
            'start_date'    => ['required', 'date'],
            'salary'        => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();

        try {
            // 1. Auto-generate credentials
            $plainPassword = Str::random(10);

            // 2. Create User account
            $user = User::create([
                'name'              => $request->first_name . ' ' . $request->last_name,
                'email'             => $request->email,
                'password'          => Hash::make($plainPassword),
                'role'              => 'employee',
                'email_verified_at' => now(),
            ]);

            // 3. Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo     = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('images/employees'), $photoName);
                $photoPath = 'images/employees/' . $photoName;
            }

            // 4. Create Employee profile
            $employee = Employee::create([
                'nid'        => $request->nid,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'gender'     => $request->gender,
                'dob'        => $request->dob,
                'phone'      => $request->phone,
                'email'      => $request->email,
                'photo'      => $photoPath,
                'district'   => $request->district,
                'sector'     => $request->sector,
                'user_id'    => $user->id,
            ]);

            // 5. Create initial employment record linked to this employer
            EmploymentRecord::create([
                'employee_id'   => $employee->id,
                'employer_id'   => Auth::user()->employer->id,
                'position'      => $request->position,
                'department'    => $request->department,
                'contract_type' => $request->contract_type,
                'start_date'    => $request->start_date,
                'salary'        => $request->salary,
            ]);

            // 6. Send credentials to employee's email
            Mail::to($user->email)->send(
                new EmployeeCredentialsMail($user, $plainPassword)
            );

            DB::commit();

            return redirect()
                ->route('employer.search.result', $employee->id)
                ->with('success', "Employee profile created successfully. Login credentials have been sent to {$user->email}.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    // Add this method to EmployeeSearchController:

    public function link(Request $request, Employee $employee)
    {
        $request->validate([
            'job_title'      => ['required', 'string', 'max:150'],
            'department'    => ['nullable', 'string', 'max:150'],
            'employment_status' => ['required', 'in:active,resigned,terminated'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['nullable', 'date'],
        ]);

        $alreadyLinked = $employee->employmentRecords()
            ->where('employer_id', Auth::id())
            ->whereNull('end_date')
            ->exists();

        if ($alreadyLinked) {
            return back()->with('warning', 'This employee is already in your active workforce.');
        }

        EmploymentRecord::create([
            'employee_id'       => $employee->id,
            'employer_id'       => Auth::user()->employer->id,
            'job_title'      => $request->job_title,
            'department'    => $request->department,
            'employment_status' => $request->employment_status,
            'start_date'    => $request->start_date,
            'end_date'        => $request->end_date,
        ]);

        return redirect()
            ->route('employer.search.result', $employee)
            ->with('success', "{$employee->first_name} {$employee->last_name} has been added to your workforce.");
    }
}
