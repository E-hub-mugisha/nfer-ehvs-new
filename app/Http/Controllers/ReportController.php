<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispute;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $from = $request->filled('from') ? $request->from : now()->subMonths(5)->startOfMonth()->toDateString();
        $to   = $request->filled('to')   ? $request->to   : now()->endOfMonth()->toDateString();
 
        // ── Summary KPIs ─────────────────────────────────────────────────
        $summary = [
            'employees'   => Employee::whereBetween('created_at', [$from, $to])->count(),
            'employers'   => Employer::whereBetween('created_at', [$from, $to])->count(),
            'records'     => EmploymentRecord::whereBetween('created_at', [$from, $to])->count(),
            'disputes'    => Dispute::whereBetween('created_at', [$from, $to])->count(),
            'transfers'   => TransferRequest::whereBetween('created_at', [$from, $to])->count(),
        ];
 
        // ── Monthly employee registrations ────────────────────────────────
        $monthlyEmployees = Employee::select(
                DB::raw("DATE_FORMAT(created_at,'%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as sort_key"),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();
 
        // ── Monthly employer registrations ────────────────────────────────
        $monthlyEmployers = Employer::select(
                DB::raw("DATE_FORMAT(created_at,'%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as sort_key"),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();
 
        // ── Shared monthly labels (union of both sets) ────────────────────
        $allMonths = $monthlyEmployees->pluck('month', 'sort_key')
            ->union($monthlyEmployers->pluck('month', 'sort_key'))
            ->sortKeys();
 
        $sharedLabels     = $allMonths->values()->toArray();
        $sharedSortKeys   = $allMonths->keys()->toArray();
 
        $empMap  = $monthlyEmployees->pluck('total', 'sort_key')->toArray();
        $erMap   = $monthlyEmployers->pluck('total', 'sort_key')->toArray();
 
        $employeeCounts = array_map(fn ($k) => $empMap[$k] ?? 0, $sharedSortKeys);
        $employerCounts = array_map(fn ($k) => $erMap[$k]  ?? 0, $sharedSortKeys);
 
        // ── Monthly disputes ──────────────────────────────────────────────
        $monthlyDisputes = Dispute::select(
                DB::raw("DATE_FORMAT(created_at,'%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as sort_key"),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();
 
        $disputeMap    = $monthlyDisputes->pluck('total', 'sort_key')->toArray();
        $disputeCounts = array_map(fn ($k) => $disputeMap[$k] ?? 0, $sharedSortKeys);
 
        // ── Monthly transfers ─────────────────────────────────────────────
        $monthlyTransfers = TransferRequest::select(
                DB::raw("DATE_FORMAT(created_at,'%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as sort_key"),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get();
 
        $transferMap    = $monthlyTransfers->pluck('total', 'sort_key')->toArray();
        $transferCounts = array_map(fn ($k) => $transferMap[$k] ?? 0, $sharedSortKeys);
 
        // ── Disputes by status ────────────────────────────────────────────
        $disputesByStatus = Dispute::select('status', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
 
        // ── Transfers by status ───────────────────────────────────────────
        $transfersByStatus = TransferRequest::select('status', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
 
        // ── Employment by status ──────────────────────────────────────────
        $employmentByStatus = EmploymentRecord::select('employment_status', DB::raw('count(*) as total'))
            ->groupBy('employment_status')
            ->pluck('total', 'employment_status')
            ->toArray();
 
        // ── Gender breakdown ──────────────────────────────────────────────
        $genderBreakdown = Employee::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender')
            ->toArray();
 
        // ── Top districts ─────────────────────────────────────────────────
        $topDistricts = Employee::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
 
        // ── Top employers by record count ─────────────────────────────────
        $topEmployers = EmploymentRecord::select('employer_id', DB::raw('count(*) as total'))
            ->with('employer:id,company_name')
            ->groupBy('employer_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
 
        // ── Dispute resolution rate (period) ──────────────────────────────
        $periodDisputes = array_sum($disputesByStatus);
        $resolvedCount  = $disputesByStatus['resolved'] ?? 0;
        $resolutionRate = $periodDisputes > 0 ? round(($resolvedCount / $periodDisputes) * 100, 1) : 0;
 
        // ── Transfer approval rate (period) ───────────────────────────────
        $decidedTransfers   = ($transfersByStatus['approved'] ?? 0) + ($transfersByStatus['rejected'] ?? 0);
        $approvalRate       = $decidedTransfers > 0
            ? round((($transfersByStatus['approved'] ?? 0) / $decidedTransfers) * 100, 1)
            : 0;
 
        return view('government.reports.index', compact(
            'from', 'to', 'summary',
            'sharedLabels',
            'employeeCounts', 'employerCounts',
            'disputeCounts', 'transferCounts',
            'disputesByStatus', 'transfersByStatus',
            'employmentByStatus', 'genderBreakdown',
            'topDistricts', 'topEmployers',
            'resolutionRate', 'approvalRate'
        ));
    }
 
    // ── CSV Export ────────────────────────────────────────────────────────
    public function export(Request $request, string $type): StreamedResponse
    {
        $from = $request->get('from', now()->subYear()->toDateString());
        $to   = $request->get('to',   now()->toDateString());
 
        $filename = "{$type}_report_" . now()->format('Ymd_His') . '.csv';
 
        return response()->streamDownload(function () use ($type, $from, $to) {
 
            $out = fopen('php://output', 'w');
 
            match ($type) {
                'employees' => $this->exportEmployees($out, $from, $to),
                'transfers' => $this->exportTransfers($out, $from, $to),
                'disputes'  => $this->exportDisputes($out, $from, $to),
                'records'   => $this->exportRecords($out, $from, $to),
                default     => null,
            };
 
            fclose($out);
 
        }, $filename, ['Content-Type' => 'text/csv']);
    }
 
    // ── Export helpers ─────────────────────────────────────────────────────
    private function exportEmployees($out, $from, $to): void
    {
        fputcsv($out, ['ID', 'NID', 'First Name', 'Last Name', 'Gender', 'DOB', 'Phone', 'Email', 'District', 'Registered At']);
        Employee::whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->each(function ($e) use ($out) {
                fputcsv($out, [$e->id, $e->nid, $e->first_name, $e->last_name, $e->gender, $e->dob, $e->phone, $e->email, $e->district, $e->created_at]);
            });
    }
 
    private function exportTransfers($out, $from, $to): void
    {
        fputcsv($out, ['ID', 'Employee', 'NID', 'From Employer', 'To Employer', 'Proposed Title', 'Proposed Dept', 'Start Date', 'Status', 'Reason', 'Rejection Reason', 'Responded At', 'Created At']);
        TransferRequest::with(['employee', 'currentEmployer', 'requestingEmployer'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->each(function ($t) use ($out) {
                fputcsv($out, [
                    $t->id,
                    $t->employee?->full_name,
                    $t->employee?->nid,
                    $t->currentEmployer?->company_name,
                    $t->requestingEmployer?->company_name,
                    $t->proposed_job_title,
                    $t->proposed_department,
                    $t->proposed_start_date,
                    $t->status,
                    $t->reason,
                    $t->rejection_reason,
                    $t->responded_at,
                    $t->created_at,
                ]);
            });
    }
 
    private function exportDisputes($out, $from, $to): void
    {
        fputcsv($out, ['ID', 'Employee', 'NID', 'Employer', 'Job Title', 'Description', 'Status', 'Created At']);
        Dispute::with(['employee', 'employmentRecord.employer'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->each(function ($d) use ($out) {
                fputcsv($out, [
                    $d->id,
                    $d->employee?->full_name,
                    $d->employee?->nid,
                    $d->employmentRecord?->employer?->company_name,
                    $d->employmentRecord?->job_title,
                    $d->description,
                    $d->status,
                    $d->created_at,
                ]);
            });
    }
 
    private function exportRecords($out, $from, $to): void
    {
        fputcsv($out, ['ID', 'Employee', 'NID', 'Employer', 'Job Title', 'Department', 'Start Date', 'End Date', 'Status', 'Exit Reason', 'Created At']);
        EmploymentRecord::with(['employee', 'employer'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->each(function ($r) use ($out) {
                fputcsv($out, [
                    $r->id,
                    $r->employee?->full_name,
                    $r->employee?->nid,
                    $r->employer?->company_name,
                    $r->job_title,
                    $r->department,
                    $r->start_date,
                    $r->end_date,
                    $r->employment_status,
                    $r->exit_reason,
                    $r->created_at,
                ]);
            });
    }
}
