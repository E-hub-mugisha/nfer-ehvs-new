<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $query = TransferRequest::with([
            'employee',
            'requestingEmployer',
            'currentEmployer',
            'currentEmploymentRecord',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas(
                'employee',
                fn($q) =>
                $q->where('full_name', 'like', "%{$request->search}%")
            )->orWhereHas(
                'requestingEmployer',
                fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            );
        }

        return view('admin.transfers.index', [
            'transferRequests' => $query->paginate(15),
            'pendingCount'     => TransferRequest::where('status', 'pending')->count(),
            'totalCount'       => TransferRequest::count(),
        ]);
    }

    public function show(TransferRequest $transferRequest)
    {
        $transferRequest->load([
            'employee',
            'requestingEmployer',
            'currentEmployer',
            'currentEmploymentRecord',
        ]);

        return view('admin.transfers.show', compact('transferRequest'));
    }

    public function approve(TransferRequest $transferRequest)
    {
        $transferRequest->update(['status' => 'approved', 'responded_at' => now()]);
        return redirect()->route('admin.transfer-requests.show', $transferRequest)
            ->with('success', 'Transfer approved.');
    }

    public function reject(Request $request, TransferRequest $transferRequest)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);
        $transferRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'responded_at'     => now(),
        ]);
        return redirect()->route('admin.transfer-requests.show', $transferRequest)
            ->with('success', 'Transfer rejected.');
    }

    /**
     * Permanently delete a transfer request.
     */
    public function destroy(TransferRequest $transferRequest)
    {
        $transferRequest->delete();
 
        return redirect()
            ->route('admin.transfer-requests.index')
            ->with('success', 'Transfer request #' . $transferRequest->id . ' deleted.');
    }
}
