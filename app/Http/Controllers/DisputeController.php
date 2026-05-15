<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    /**
     * Display all disputes for logged in employee
     */
    public function index()
    {
        $employee = Auth::user()->employee;

        $disputes = Dispute::with([
                'employmentRecord.employer'
            ])
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        return view('disputes.index', compact('disputes'));
    }

    /**
     * Show create dispute form
     */
    public function create()
    {
        $employee = Auth::user()->employee;

        $records = EmploymentRecord::with('employer')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        return view('disputes.create', compact('records'));
    }

    /**
     * Store new dispute
     */
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $validated = $request->validate([
            'employment_record_id' => 'required|exists:employment_records,id',
            'description' => 'required|string|min:10',
            'evidence' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // Upload evidence file
        $evidencePath = null;

        if ($request->hasFile('evidence')) {

            $evidencePath = $request->file('evidence')
                ->store('disputes/evidence', 'public');
        }

        // Create dispute
        Dispute::create([
            'employee_id' => $employee->id,
            'employment_record_id' => $validated['employment_record_id'],
            'description' => $validated['description'],
            'evidence' => $evidencePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('disputes.index')
            ->with('success', 'Dispute submitted successfully.');
    }

    /**
     * Show dispute details
     */
    public function show($id)
    {
        $employee = Auth::user()->employee;

        $dispute = Dispute::with([
                'employee',
                'employmentRecord.employer'
            ])
            ->where('employee_id', $employee->id)
            ->findOrFail($id);

        return view('disputes.show', compact('dispute'));
    }
}