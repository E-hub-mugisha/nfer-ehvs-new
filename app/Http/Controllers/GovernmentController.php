<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransferRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GovernmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // If user role is government and profile doesn't exist
        if ($user->role === 'government') {

            $government = Government::where('user_id', $user->id)->first();

            if (!$government) {
                return redirect()
                    ->route('government.profile.create')
                    ->with('warning', 'Please complete your government profile first.');
            }
        }
        // ── KPI Cards ─────────────────────────────────────────────────────────
        $totalEmployees        = Employee::count();
        $totalEmployers        = Employer::count();
        $totalEmploymentRecords = EmploymentRecord::count();
        $totalDisputes         = Dispute::count();
        $totalTransferRequests = TransferRequest::count();
        $pendingDisputes       = Dispute::where('status', 'pending')->count();
        $pendingTransfers      = TransferRequest::where('status', 'pending')->count();
        $activeEmployments     = EmploymentRecord::where('employment_status', 'active')->count();

        // ── Dispute Status Breakdown (Doughnut) ───────────────────────────────
        $disputesByStatus = Dispute::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Transfer Request Status Breakdown (Horizontal Bar) ────────────────
        $transfersByStatus = TransferRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Employment Records by Status (Bar) ────────────────────────────────
        $recordsByStatus = EmploymentRecord::select('employment_status', DB::raw('count(*) as total'))
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status')
            ->toArray();

        // ── Monthly New Employees – last 12 months (Line Chart) ───────────────
        $monthlyEmployees = Employee::select(
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as sort_key"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();

        $monthlyLabels = $monthlyEmployees->pluck('month')->toArray();
        $monthlyCounts = $monthlyEmployees->pluck('total')->toArray();

        // ── Monthly Employment Records Created – last 12 months ───────────────
        $monthlyRecords = EmploymentRecord::select(
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as sort_key"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();

        $monthlyRecordCounts = $monthlyRecords->pluck('total')->toArray();

        // Use employee months as shared x-axis labels; pad records array to same length
        $recordMonthMap = $monthlyRecords->pluck('total', 'month')->toArray();
        $monthlyRecordCounts = array_map(
            fn($label) => $recordMonthMap[$label] ?? 0,
            $monthlyLabels
        );

        // ── Top Employers by Active Headcount ─────────────────────────────────
        $topEmployers = EmploymentRecord::select(
            'employer_id',
            DB::raw('count(*) as headcount')
        )
            ->where('employment_status', 'active')
            ->with('employer:id,company_name')
            ->groupBy('employer_id')
            ->orderByDesc('headcount')
            ->limit(7)
            ->get();

        $topEmployerLabels = $topEmployers->map(fn($r) => $r->employer?->company_name ?? 'Unknown')->toArray();
        $topEmployerCounts = $topEmployers->pluck('headcount')->toArray();

        // ── Recent Disputes ───────────────────────────────────────────────────
        $recentDisputes = Dispute::with(['employee:id,first_name,last_name', 'employmentRecord.employer:id,company_name'])
            ->latest()
            ->limit(6)
            ->get();

        // ── Recent Transfer Requests ──────────────────────────────────────────
        $recentTransfers = TransferRequest::with([
            'employee:id,first_name,last_name',
            'requestingEmployer:id,company_name',
            'currentEmployer:id,company_name',
        ])
            ->latest()
            ->limit(6)
            ->get();

        // ── Growth delta (compared with previous 30 days) ─────────────────────
        $employeeGrowth = $this->growthRate(Employee::class);
        $employerGrowth = $this->growthRate(Employer::class);
        $recordGrowth   = $this->growthRate(EmploymentRecord::class);
        $disputeGrowth  = $this->growthRate(Dispute::class);

        return view('government.dashboard', compact(
            'totalEmployees',
            'totalEmployers',
            'totalEmploymentRecords',
            'totalDisputes',
            'totalTransferRequests',
            'pendingDisputes',
            'pendingTransfers',
            'activeEmployments',
            'disputesByStatus',
            'transfersByStatus',
            'recordsByStatus',
            'monthlyLabels',
            'monthlyCounts',
            'monthlyRecordCounts',
            'topEmployerLabels',
            'topEmployerCounts',
            'recentDisputes',
            'recentTransfers',
            'employeeGrowth',
            'employerGrowth',
            'recordGrowth',
            'disputeGrowth'
        ));
    }

    // ── Helper: % growth vs prior 30-day window ───────────────────────────────
    private function growthRate(string $model): array
    {
        $current  = $model::where('created_at', '>=', now()->subDays(30))->count();
        $previous = $model::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();

        if ($previous === 0) {
            return ['value' => $current > 0 ? 100 : 0, 'direction' => 'up'];
        }

        $pct = round((($current - $previous) / $previous) * 100, 1);
        return [
            'value'     => abs($pct),
            'direction' => $pct >= 0 ? 'up' : 'down',
        ];
    }

    public function approveEmployer($id)
    {
        $employer = Employer::findOrFail($id);

        $employer->update([

            'status' => 'verified',

            'verified_by' => Auth::id(),

            'verified_at' => now(),

            'verification_notes' =>
            'Verified by government official'

        ]);

        return back()->with(
            'success',
            'Employer Verified Successfully'
        );
    }

    public function indexEmploymentRecord(Request $request)
    {
        $query = EmploymentRecord::with(['employee', 'employer']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas(
                        'employee',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'employer',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    );
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        $departments = EmploymentRecord::distinct()->pluck('department')->filter()->sort()->values();
        $statuses = EmploymentRecord::distinct()->pluck('employment_status')->filter()->sort()->values();

        return view('government.employees.records.index', compact('records', 'departments', 'statuses'));
    }

    public function show(EmploymentRecord $employmentRecord)
    {
        $employmentRecord->load(['employee', 'employer', 'disputes']);

        if (request()->expectsJson()) {
            return response()->json($employmentRecord);
        }

        return view('government.employees.records.show', compact('employmentRecord'));
    }

    public function indexTransferRequest(Request $request)
    {
        $query = TransferRequest::with([
            'employee:id,first_name,last_name,nid,photo',
            'requestingEmployer:id,company_name,rdb_number',
            'currentEmployer:id,company_name',
            'currentEmploymentRecord:id,job_title,department',
        ]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by requesting employer
        if ($request->filled('employer_id')) {
            $query->where('requesting_employer_id', $request->employer_id);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Search by employee name / NID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nid', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $transfers = $query->latest()->paginate(15)->withQueryString();

        // Summary counts for filter pills
        $counts = TransferRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('government.transfers.index', compact('transfers', 'counts'));
    }

    // ── Show / Detail Modal ───────────────────────────────────────────────
    public function showTransferRequest(TransferRequest $transferRequest)
    {
        $transferRequest->load([
            'employee',
            'requestingEmployer',
            'currentEmployer',
            'currentEmploymentRecord.employer',
        ]);

        return response()->json($transferRequest);
    }

    // ── Approve ───────────────────────────────────────────────────────────
    public function approve(TransferRequest $transferRequest)
    {
        abort_if(!$transferRequest->isPending(), 422, 'Only pending requests can be approved.');

        DB::transaction(function () use ($transferRequest) {

            // 1. Mark old employment record as terminated
            EmploymentRecord::where('id', $transferRequest->current_employment_record_id)
                ->update([
                    'employment_status' => 'terminated',
                    'end_date'          => now()->toDateString(),
                    'exit_reason'       => 'mutual-agreement',
                    'remarks'           => "Transferred to {$transferRequest->requestingEmployer?->company_name}",
                ]);

            // 2. Create new employment record at requesting employer
            EmploymentRecord::create([
                'employee_id'       => $transferRequest->employee_id,
                'employer_id'       => $transferRequest->requesting_employer_id,
                'job_title'         => $transferRequest->proposed_job_title,
                'department'        => $transferRequest->proposed_department,
                'start_date'        => $transferRequest->proposed_start_date ?? now()->toDateString(),
                'employment_status' => 'active',
            ]);

            // 3. Update transfer request
            $transferRequest->update([
                'status'       => 'approved',
                'responded_at' => now(),
            ]);
        });

        return back()->with('success', 'Transfer request approved and employment record updated.');
    }

    // ── Reject ────────────────────────────────────────────────────────────
    public function reject(Request $request, TransferRequest $transferRequest)
    {
        abort_if(!$transferRequest->isPending(), 422, 'Only pending requests can be rejected.');

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejecting this transfer request.',
            'rejection_reason.min'      => 'Rejection reason must be at least 10 characters so the employer understands the decision.',
        ]);

        $transferRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'responded_at'     => now(),
        ]);

        return back()->with('success', 'Transfer request rejected.');
    }

    public function create()
    {
        $government = Government::where('user_id', Auth::id())->first();

        if ($government) {
            return redirect()->route('government.dashboard');
        }

        return view('government.profile.create');
    }

    /**
     * Normalize raw profile input before validation.
     */
    private function normalizeGovernmentProfileInput(Request $request): void
    {
        $request->merge([
            'name'              => $request->name ? trim($request->name) : null,
            'country'           => $request->country ? trim($request->country) : null,
            'contact_email'     => $request->contact_email ? strtolower(trim($request->contact_email)) : null,
            'website'           => $request->website ? trim($request->website) : null,
            'established_year'  => $request->established_year !== null ? (int) $request->established_year : null,
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeGovernmentProfileInput($request);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'min:2', 'max:255'],
            'country'           => ['required', 'string', 'max:100'],
            'government_type'   => ['required', Rule::in(['Ministry', 'Department', 'Agency', 'Authority'])],
            'established_year'  => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'contact_email'     => [
                'required', 'email:rfc,dns', 'max:255',
                'unique:governments,contact_email', 'unique:users,email',
            ],
            'website'           => ['nullable', 'url', 'max:255', 'regex:/^https?:\/\//'],
        ], [
            'name.min'                => 'Government name must be at least 2 characters.',
            'government_type.in'      => 'Please select a valid government entity type.',
            'established_year.max'    => 'Established year cannot be in the future.',
            'established_year.min'    => 'Established year must be 1900 or later.',
            'contact_email.unique'    => 'This email is already registered to another government entity or user.',
            'website.regex'           => 'Website must start with http:// or https://.',
        ]);

        Government::create([
            'user_id'           => Auth::id(),
            'name'              => $validated['name'],
            'country'           => $validated['country'],
            'government_type'   => $validated['government_type'],
            'established_year'  => $validated['established_year'] ?? null,
            'contact_email'     => $validated['contact_email'],
            'website'           => $validated['website'] ?? null,
            'is_verified'       => false,
        ]);

        return redirect()
            ->route('government.dashboard')
            ->with('success', 'Government profile completed successfully.');
    }
}