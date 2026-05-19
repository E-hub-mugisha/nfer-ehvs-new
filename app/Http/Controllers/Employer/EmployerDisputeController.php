<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dispute;

class EmployerDisputeController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employer;

        $disputes = Dispute::with([
                'employee',
                'employmentRecord'
            ])
            ->whereHas('employmentRecord', function ($query) use ($employer) {
                $query->where('employer_id', $employer->id);
            })
            ->latest()
            ->paginate(10);

        return view('employer.disputes.index', compact('disputes'));
    }
    public function show($id)
    {
        $employer = Auth::user()->employer;

        $dispute = Dispute::with([
                'employee',
                'employmentRecord'
            ])
            ->whereHas('employmentRecord', function ($query) use ($employer) {
                $query->where('employer_id', $employer->id);
            })
            ->findOrFail($id);

        return view('employer.disputes.show', compact('dispute'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,rejected'
        ]);

        $employer = Auth::user()->employer;

        $dispute = Dispute::whereHas('employmentRecord', function ($query) use ($employer) {
                $query->where('employer_id', $employer->id);
            })
            ->findOrFail($id);

        $dispute->status = $request->status;
        $dispute->save();

        return redirect()
            ->back()
            ->with('success', 'Dispute status updated successfully.');
    }
}
