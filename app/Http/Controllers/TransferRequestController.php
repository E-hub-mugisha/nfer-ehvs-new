<?php

namespace App\Http\Controllers;

use App\Models\TransferRequest;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferRequestController extends Controller
{
    private function currentEmployer()
    {
        return Auth::user()->employer;
    }

    // ── Store a new transfer request ─────────────────────────────────────────
    public function store(Request $request, Employee $employee)
    {
        $employer = $this->currentEmployer();

        $data = $request->validate([
            'proposed_job_title'   => 'required|string|max:255',
            'proposed_department'  => 'nullable|string|max:255',
            'proposed_start_date'  => 'required|date|after_or_equal:today',
            'reason'               => 'nullable|string|max:1000',
        ]);

        // Find the employee's current active employment record
        $currentRecord = $employee->employmentRecords()
            ->whereNull('end_date')
            ->where('employment_status', 'active')
            ->latest()
            ->firstOrFail();

        // Guard: don't allow duplicate pending requests
        $alreadyRequested = TransferRequest::where('employee_id', $employee->id)
            ->where('requesting_employer_id', $employer->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'You already have a pending transfer request for this employee.');
        }

        TransferRequest::create([
            'employee_id'                  => $employee->id,
            'requesting_employer_id'       => $employer->id,
            'current_employer_id'          => $currentRecord->employer_id,
            'current_employment_record_id' => $currentRecord->id,
            'proposed_job_title'           => $data['proposed_job_title'],
            'proposed_department'          => $data['proposed_department'] ?? null,
            'proposed_start_date'          => $data['proposed_start_date'],
            'reason'                       => $data['reason'] ?? null,
            'status'                       => 'pending',
        ]);

        return back()->with('success', 'Transfer request sent. You will be notified once the current employer responds.');
    }

    // ── List requests sent by this employer ──────────────────────────────────
    public function sent()
    {
        $requests = TransferRequest::with(['employee', 'currentEmployer'])
            ->where('requesting_employer_id', $this->currentEmployer()->id)
            ->latest()
            ->paginate(15);

        return view('employer.transfers.sent', compact('requests'));
    }

    // ── List requests received by this employer ───────────────────────────────
    public function received()
    {
        $requests = TransferRequest::with(['employee', 'requestingEmployer', 'currentEmploymentRecord'])
            ->where('current_employer_id', $this->currentEmployer()->id)
            ->latest()
            ->paginate(15);

        return view('employer.transfers.received', compact('requests'));
    }

    // ── Approve ───────────────────────────────────────────────────────────────
    public function approve(Request $request, TransferRequest $transfer)
    {
        $this->authorizeAsCurrentEmployer($transfer);

        $data = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($transfer, $data) {
            // 1. Close the current employment record
            $transfer->currentEmploymentRecord->update([
                'end_date'           => now()->toDateString(),
                'employment_status'  => 'resigned',   // or 'transferred'
                'exit_reason'        => 'Transferred to ' . $transfer->requestingEmployer->company_name,
            ]);

            // 2. Open a new record for the requesting employer
            EmploymentRecord::create([
                'employee_id'        => $transfer->employee_id,
                'employer_id'        => $transfer->requesting_employer_id,
                'job_title'          => $transfer->proposed_job_title,
                'department'         => $transfer->proposed_department,
                'start_date'         => $transfer->proposed_start_date,
                'employment_status'  => 'active',
            ]);

            // 3. Mark transfer as approved
            $transfer->update([
                'status'       => 'approved',
                'rejection_reason' => $data['rejection_reason'] ?? null,
                'responded_at' => now(),
            ]);
        });

        return back()->with('success', 'Transfer approved. The employee has been moved to the requesting employer.');
    }

    // ── Reject ────────────────────────────────────────────────────────────────
    public function reject(Request $request, TransferRequest $transfer)
    {
        $this->authorizeAsCurrentEmployer($transfer);

        $data = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $transfer->update([
            'status'           => 'rejected',
            'rejection_reason' => $data['rejection_reason'] ?? null,
            'responded_at'     => now(),
        ]);

        return back()->with('success', 'Transfer request has been rejected.');
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function authorizeAsCurrentEmployer(TransferRequest $transfer): void
    {
        abort_unless(
            $transfer->current_employer_id === $this->currentEmployer()->id,
            403
        );
        abort_unless($transfer->isPending(), 422, 'This request has already been responded to.');
    }
}
