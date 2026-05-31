<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class GovernmentDisputeController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispute::with(['employee', 'employmentRecord'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        $disputes = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all'          => Dispute::count(),
            'pending'      => Dispute::where('status', 'pending')->count(),
            'under_review' => Dispute::where('status', 'under_review')->count(),
            'resolved'     => Dispute::where('status', 'resolved')->count(),
            'rejected'     => Dispute::where('status', 'rejected')->count(),
        ];

        return view('government.disputes.index', compact('disputes', 'statusCounts'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['employee', 'employmentRecord']);

        if ($dispute->employmentRecord) {
            $dispute->employmentRecord->load('employer');
        }

        return view('government.disputes.show', compact('dispute'));
    }

    public function updateStatus(Request $request, Dispute $dispute)
    {
        $request->validate([
            'status' => ['required', 'in:pending,under-review,resolved,rejected'],
        ]);

        $dispute->update(['status' => $request->status]);

        return redirect()
            ->route('government.disputes.show', $dispute)
            ->with('success', 'Dispute status updated to ' . ucfirst(str_replace('_', ' ', $request->status)) . '.');
    }
}