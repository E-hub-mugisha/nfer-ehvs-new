<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class EmployeeRecordController extends Controller
{
    // ── Employment Records: Index ──────────────────────────────────────
    public function indexEmploymentRecord(Request $request)
    {
        $query = EmploymentRecord::with(['employee', 'employer']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas(
                        'employee',
                        fn($q) =>
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('nid', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'employer',
                        fn($q) =>
                        $q->where('company_name', 'like', "%{$search}%")
                    );
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('employment_status', $status);
        }

        // Filter by employer
        if ($employerId = $request->get('employer_id')) {
            $query->where('employer_id', $employerId);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDir   = $request->get('dir', 'desc');
        $allowed   = ['created_at', 'start_date', 'end_date', 'job_title', 'employment_status'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $records = $query->paginate(20)->withQueryString();

        // Stats for the filter bar
        $stats = [
            'total'      => EmploymentRecord::count(),
            'active'     => EmploymentRecord::where('employment_status', 'active')->count(),
            'inactive'   => EmploymentRecord::where('employment_status', 'inactive')->count(),
            'terminated' => EmploymentRecord::where('employment_status', 'terminated')->count(),
        ];

        $employers = Employer::orderBy('company_name')->get(['id', 'company_name']);

        return view('admin.employment-records.index', compact('records', 'stats', 'employers'));
    }

    // ── Employment Records: Show ───────────────────────────────────────
    public function show(EmploymentRecord $employmentRecord)
    {
        $employmentRecord->load([
            'employee.user',
            'employer',
            'disputes',
        ]);

        // All records for this employee (employment history)
        $history = EmploymentRecord::with('employer')
            ->where('employee_id', $employmentRecord->employee_id)
            ->orderByDesc('start_date')
            ->get();

        // Transfer requests linked to this record
        $transfers = TransferRequest::with(['requestingEmployer', 'currentEmployer'])
            ->where('current_employment_record_id', $employmentRecord->id)
            ->orWhere(function ($q) use ($employmentRecord) {
                $q->where('employee_id', $employmentRecord->employee_id);
            })
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.employment-records.show', compact('employmentRecord', 'history', 'transfers'));
    }

    public function destroy(EmploymentRecord $employmentRecord)
    {
        $employmentRecord->delete();

        return redirect()
            ->route('admin.employment-records.index')
            ->with('success', 'Employment record deleted with all related records.');
    }
}
