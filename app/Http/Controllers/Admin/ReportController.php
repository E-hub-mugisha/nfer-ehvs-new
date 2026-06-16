<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '12'); // months
 
        // ── Summary KPIs ─────────────────────────────────────────────
        $summary = [
            'total_employees'    => Employee::count(),
            'total_employers'    => Employer::count(),
            'approved_employers' => Employer::where('status', 'approved')->count(),
            'pending_employers'  => Employer::where('status', 'pending')->count(),
            'active_records'     => EmploymentRecord::where('employment_status', 'active')->count(),
            'total_records'      => EmploymentRecord::count(),
            'total_disputes'     => Dispute::count(),
            'open_disputes'      => Dispute::where('status', 'open')->count(),
            'resolved_disputes'  => Dispute::where('status', 'resolved')->count(),
            'total_transfers'    => TransferRequest::count(),
            'pending_transfers'  => TransferRequest::where('status', 'pending')->count(),
            'approved_transfers' => TransferRequest::where('status', 'approved')->count(),
        ];
 
        // ── Monthly trends (employees, employers, records, disputes) ──
        $months = (int) $period;
 
        $monthlyEmployees = Employee::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month');
 
        $monthlyEmployers = Employer::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month');
 
        $monthlyRecords = EmploymentRecord::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month');
 
        $monthlyDisputes = Dispute::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month');
 
        // Build a complete month range (fill gaps with 0)
        $allMonths = collect();
        for ($i = $months - 1; $i >= 0; $i--) {
            $allMonths->push(now()->subMonths($i)->format('Y-m'));
        }
 
        $trendLabels   = $allMonths->map(fn($m) => \Carbon\Carbon::parse($m)->format('M y'))->values();
        $trendEmployees = $allMonths->map(fn($m) => $monthlyEmployees[$m] ?? 0)->values();
        $trendEmployers = $allMonths->map(fn($m) => $monthlyEmployers[$m] ?? 0)->values();
        $trendRecords   = $allMonths->map(fn($m) => $monthlyRecords[$m] ?? 0)->values();
        $trendDisputes  = $allMonths->map(fn($m) => $monthlyDisputes[$m] ?? 0)->values();
 
        // ── Employment records by status ──────────────────────────────
        $recordsByStatus = EmploymentRecord::select('employment_status', DB::raw('count(*) as total'))
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status');
 
        // ── Disputes by status ────────────────────────────────────────
        $disputesByStatus = Dispute::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
 
        // ── Transfer requests by status ───────────────────────────────
        $transfersByStatus = TransferRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
 
        // ── Top employers by employee count ───────────────────────────
        $topEmployers = Employer::withCount('employmentRecords')
            ->orderByDesc('employment_records_count')
            ->take(10)
            ->get(['id', 'company_name', 'status']);
 
        // ── Employees by district (geographic spread) ─────────────────
        $employeesByDistrict = Employee::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderByDesc('total')
            ->take(10)
            ->pluck('total', 'district');
 
        // ── Dispute resolution rate ───────────────────────────────────
        $resolutionRate = $summary['total_disputes'] > 0
            ? round(($summary['resolved_disputes'] / $summary['total_disputes']) * 100, 1)
            : 0;
 
        // ── Employer approval rate ────────────────────────────────────
        $approvalRate = ($summary['total_employers'] > 0)
            ? round(($summary['approved_employers'] / $summary['total_employers']) * 100, 1)
            : 0;
 
        // ── Active employment rate ────────────────────────────────────
        $activeRate = $summary['total_records'] > 0
            ? round(($summary['active_records'] / $summary['total_records']) * 100, 1)
            : 0;
 
        // ── Transfer approval rate ────────────────────────────────────
        $transferApprovalRate = $summary['total_transfers'] > 0
            ? round(($summary['approved_transfers'] / $summary['total_transfers']) * 100, 1)
            : 0;
 
        $rates = compact('resolutionRate', 'approvalRate', 'activeRate', 'transferApprovalRate');
 
        return view('admin.reports.index', compact(
            'summary',
            'trendLabels',
            'trendEmployees',
            'trendEmployers',
            'trendRecords',
            'trendDisputes',
            'recordsByStatus',
            'disputesByStatus',
            'transfersByStatus',
            'topEmployers',
            'employeesByDistrict',
            'rates',
            'period',
        ));
    }
 
    /**
     * Export report as CSV.
     * Route: GET /government/reports/export/{type}
     * Types: summary | employees | employers | records | disputes | transfers
     */
    public function export(string $type)
    {
        $filename = "report_{$type}_" . now()->format('Y-m-d') . '.csv';
 
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
 
        $callback = match ($type) {
            'employees' => function () {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'NID', 'First Name', 'Last Name', 'Gender', 'DOB', 'Phone', 'Email', 'District', 'Sector', 'Registered At']);
                Employee::chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->id, $r->nid, $r->first_name, $r->last_name, $r->gender, $r->dob, $r->phone, $r->email, $r->district, $r->sector, $r->created_at]);
                    }
                });
                fclose($out);
            },
            'employers' => function () {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Company Name', 'RDB Number', 'TIN Number', 'Email', 'Phone', 'Address', 'Status', 'Registered At']);
                Employer::chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->id, $r->company_name, $r->rdb_number, $r->tin_number, $r->email, $r->phone, $r->address, $r->status, $r->created_at]);
                    }
                });
                fclose($out);
            },
            'records' => function () {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Employee', 'Employer', 'Job Title', 'Department', 'Start Date', 'End Date', 'Status', 'Exit Reason']);
                EmploymentRecord::with(['employee', 'employer'])->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [
                            $r->id,
                            optional($r->employee)->full_name,
                            optional($r->employer)->company_name,
                            $r->job_title, $r->department,
                            $r->start_date, $r->end_date,
                            $r->employment_status, $r->exit_reason,
                        ]);
                    }
                });
                fclose($out);
            },
            'disputes' => function () {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Employee', 'Employment Record ID', 'Status', 'Description', 'Filed At']);
                Dispute::with('employee')->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [$r->id, optional($r->employee)->full_name, $r->employment_record_id, $r->status, $r->description, $r->created_at]);
                    }
                });
                fclose($out);
            },
            'transfers' => function () {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Employee', 'From Employer', 'To Employer', 'Proposed Title', 'Proposed Start', 'Status', 'Requested At']);
                TransferRequest::with(['employee', 'currentEmployer', 'requestingEmployer'])->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [
                            $r->id,
                            optional($r->employee)->full_name,
                            optional($r->currentEmployer)->company_name,
                            optional($r->requestingEmployer)->company_name,
                            $r->proposed_job_title,
                            $r->proposed_start_date,
                            $r->status,
                            $r->created_at,
                        ]);
                    }
                });
                fclose($out);
            },
            default => function () {
                // Summary CSV
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Metric', 'Value']);
                $rows = [
                    ['Total Employees',    Employee::count()],
                    ['Total Employers',    Employer::count()],
                    ['Approved Employers', Employer::where('status', 'approved')->count()],
                    ['Pending Employers',  Employer::where('status', 'pending')->count()],
                    ['Total Records',      EmploymentRecord::count()],
                    ['Active Records',     EmploymentRecord::where('employment_status', 'active')->count()],
                    ['Total Disputes',     Dispute::count()],
                    ['Open Disputes',      Dispute::where('status', 'open')->count()],
                    ['Resolved Disputes',  Dispute::where('status', 'resolved')->count()],
                    ['Total Transfers',    TransferRequest::count()],
                    ['Pending Transfers',  TransferRequest::where('status', 'pending')->count()],
                    ['Approved Transfers', TransferRequest::where('status', 'approved')->count()],
                ];
                foreach ($rows as $row) fputcsv($out, $row);
                fclose($out);
            },
        };
 
        return response()->stream($callback, 200, $headers);
    }
}
