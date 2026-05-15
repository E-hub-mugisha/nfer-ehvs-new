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
        $validated = $request->validate([
            'nid'        => 'required|string|max:255|unique:employees,nid',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'gender'     => 'required|in:male,female,other',
            'dob'        => 'required|date',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email|max:255',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'district'   => 'required|string|max:255',
            'sector'     => 'required|string|max:255',
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

        $employee = Employee::with(['user', 'employmentRecords'])
            ->where('user_id', $user->id)
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
}
