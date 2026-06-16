<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispute::with(['employee', 'employmentRecord.employer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by employee name or NID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nid', 'like', "%{$search}%");
            });
        }

        $disputes = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'    => Dispute::count(),
            'pending'  => Dispute::where('status', 'pending')->count(),
            'resolved' => Dispute::where('status', 'resolved')->count(),
            'rejected' => Dispute::where('status', 'rejected')->count(),
        ];

        return view('admin.disputes.index', compact('disputes', 'stats'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['employee', 'employmentRecord.employer']);
        return view('admin.disputes.show', compact('dispute'));
    }

    public function updateStatus(Request $request, Dispute $dispute)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,resolved,rejected',
        ]);

        $dispute->update(['status' => $request->status]);

        return back()->with('success', 'Dispute status updated successfully.');
    }
}