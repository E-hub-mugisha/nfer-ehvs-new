<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernmentController extends Controller
{
    public function index()
    {
        $employees = Employee::count();

        $employers = Employer::count();

        $verifiedEmployers =
            Employer::where(
                'status',
                'verified'
            )->count();

        $records =
            EmploymentRecord::count();

        $disputes =
            Dispute::count();

        return view(
            'government.dashboard',
            compact(
                'employees',
                'employers',
                'verifiedEmployers',
                'records',
                'disputes'
            )
        );
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
}
