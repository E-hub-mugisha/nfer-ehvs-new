<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerDashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $employer = $user->employer;

        if (! $employer) {
            return redirect()->route('employer.profile.create')
                ->with('warning', 'Please complete your employer profile first.');
        }

        // Summary stats
        $totalEmployees = $employer->employmentRecords()
            ->distinct('employee_id')
            ->count('employee_id');

        $activeEmployees = $employer->employmentRecords()
            ->where('employment_status', 'active')
            ->distinct('employee_id')
            ->count('employee_id');

        $totalRecords = $employer->employmentRecords()->count();

        $recentRecords = $employer->employmentRecords()
            ->with('employee')
            ->latest()
            ->take(5)
            ->get();

        // Monthly employment records for chart (last 6 months)
        $monthlyData = $employer->employmentRecords()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->map(fn ($row) => [
                'label' => \Carbon\Carbon::create($row->year, $row->month)->format('M Y'),
                'count' => $row->count,
            ]);

        return view('employer.dashboard', compact(
            'employer',
            'totalEmployees',
            'activeEmployees',
            'totalRecords',
            'recentRecords',
            'monthlyData'
        ));
    }
}