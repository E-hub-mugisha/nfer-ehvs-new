<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmploymentRecord;
use App\Models\Dispute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    /**
     * Employee Dashboard
     */
    public function index()
    {
        // Logged in user
        $user = Auth::user();

        /**
         * Get employee profile
         */
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('employee.profile.create')
                ->with('warning', 'Please complete your employee profile first.');
        }

        /**
         * Employment records
         */
        $employmentRecords = EmploymentRecord::with('employer')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        /**
         * Recent records
         */
        $recentRecords = EmploymentRecord::with('employer')
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        /**
         * Total jobs worked
         */
        $totalJobs = $employmentRecords->count();

        /**
         * Active employment
         */
        $activeEmployment = EmploymentRecord::where(
            'employee_id',
            $employee->id
        )
            ->whereNull('end_date')
            ->exists();

        /**
         * Calculate total years of experience
         */
        $totalMonths = 0;

        foreach ($employmentRecords as $record) {

            $start = Carbon::parse($record->start_date);

            $end = $record->end_date
                ? Carbon::parse($record->end_date)
                : now();

            $months = $start->diffInMonths($end);

            $totalMonths += $months;
        }

        $totalYears = round($totalMonths / 12, 1);

        /**
         * Open disputes
         */
        $openDisputes = Dispute::where(
            'employee_id',
            $employee->id
        )
            ->whereIn('status', [
                'open',
                'pending',
                'under-review'
            ])
            ->count();

        /**
         * Recent disputes
         */
        $recentDisputes = Dispute::where(
            'employee_id',
            $employee->id
        )
            ->latest()
            ->take(5)
            ->get();

        /**
         * Dashboard analytics
         */
        $completedJobs = EmploymentRecord::where(
            'employee_id',
            $employee->id
        )
            ->whereNotNull('end_date')
            ->count();

        $currentEmployer = EmploymentRecord::with('employer')
            ->where('employee_id', $employee->id)
            ->whereNull('end_date')
            ->first();

        /**
         * Profile completion percentage
         */
        $fields = [
            'nid',
            'first_name',
            'last_name',
            'gender',
            'dob',
            'phone',
            'email',
            'photo',
            'district',
            'sector'
        ];

        $filledFields = collect($fields)
            ->filter(function ($field) use ($employee) {
                return !empty($employee->$field);
            })
            ->count();

        $profileCompletion =
            intval(
                ($filledFields / count($fields)) * 100
            );

        /**
         * Return dashboard view
         */
        return view(
            'employee.dashboard',
            compact(
                'employee',
                'employmentRecords',
                'recentRecords',
                'totalJobs',
                'activeEmployment',
                'totalYears',
                'openDisputes',
                'recentDisputes',
                'completedJobs',
                'currentEmployer',
                'profileCompletion'
            )
        );
    }

    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
{
    // Normalize input before validation
    $request->merge([
        'nid'        => preg_replace('/\s+/', '', (string) $request->nid),
        'phone'      => $request->phone ? preg_replace('/[\s\-]/', '', $request->phone) : null,
        'email'      => $request->email ? strtolower(trim($request->email)) : null,
        'first_name' => $request->first_name ? trim($request->first_name) : null,
        'last_name'  => $request->last_name ? trim($request->last_name) : null,
    ]);

    $validated = $request->validate([
        'nid'        => ['required', 'digits:16', 'unique:employees,nid'],
        'first_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
        'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\p{L}\'\-\s]+$/u'],
        'gender'     => ['required', 'in:male,female,other'],
        'dob'        => ['required', 'date', 'before:-16 years', 'after:-100 years'],
        'phone'      => ['required', 'regex:/^(\+?250|0)7[2-9][0-9]{7}$/'],
        'email'      => [
            'required', 'email:rfc,dns', 'max:255',
            'unique:employees,email', 'unique:users,email',
        ],
        'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=200,min_height=200'],
        'district'   => ['required', 'string', 'max:100'],
        'sector'     => ['required', 'string', 'max:100'],
    ], [
        'nid.digits'          => 'The National ID must be exactly 16 digits.',
        'nid.unique'          => 'An employee with this National ID already exists.',
        'first_name.regex'    => 'First name may only contain letters, spaces, and hyphens.',
        'last_name.regex'     => 'Last name may only contain letters, spaces, and hyphens.',
        'dob.before'          => 'You must be at least 16 years old.',
        'dob.after'           => 'Please enter a valid date of birth.',
        'phone.regex'         => 'Enter a valid Rwandan phone number (e.g. 078XXXXXXX or +2507XXXXXXXX).',
        'email.unique'        => 'This email is already registered.',
        'photo.dimensions'    => 'Photo must be at least 200x200 pixels.',
    ]);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')
            ->store('employees/photos', 'public');
    }

    $validated['user_id'] = Auth::id();

    Employee::create($validated);

    return redirect()->route('employee.dashboard')
        ->with('success', 'Profile created successfully.');
}

    /**
     * Show logged-in employee profile
     */
    public function show()
    {
        $user = Auth::user();

        $employee = Employee::with([
            'user',
            'employmentRecords.employer',
            'transferRequests.requestingEmployer',
            'transferRequests.currentEmployer',
            'disputes',
        ])->where('user_id', $user->id)
            ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found.');
        }

        return view('employee.profile', compact('employee'));
    }

    public function indexRecords()
    {
        $employmentRecords = EmploymentRecord::with('employer')
            ->where('employee_id', Auth::user()->employee->id)
            ->latest()
            ->get();

        return view('employment-records.index', compact('employmentRecords'));
    }

    public function showRecord($id)
    {
        $record = EmploymentRecord::with(['employee', 'employer', 'disputes'])
            ->findOrFail($id);

        return view('employment-records.show', compact('record'));
    }

    public function storeDispute(Request $request, EmploymentRecord $record)
    {
        $request->validate([
            'description' => 'required|min:20|string',
            'evidence'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $path = $request->hasFile('evidence')
            ? $request->file('evidence')->store('disputes/evidence', 'public')
            : null;

        Dispute::create([
            'employee_id'           => $request->employee_id,
            'employment_record_id'  => $request->employment_record_id,
            'description'           => $request->description,
            'evidence'              => $path,
            'status'                => 'pending',
        ]);

        return redirect()->back()->with('success', 'Dispute submitted successfully. We will review it within 7 business days.');
    }
}
