<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::latest()->paginate(10);

        return view(
            'admin.employers.index',
            compact('employers')
        );
    }

    public function show($id)
    {
        $employer = Employer::findOrFail($id);

        $employer->load([
            'user',
            'employmentRecords.employee',
            'sentTransferRequests.employee',
            'receivedTransferRequests.employee',
        ]);

        $stats = [
            'total_records'    => $employer->employmentRecords->count(),
            'active_employees' => $employer->employmentRecords->where('employment_status', 'active')->count(),
            'transfers_sent'   => $employer->sentTransferRequests->count(),
            'transfers_received' => $employer->receivedTransferRequests->count(),
        ];


        return view(
            'admin.employers.show',
            compact('employer', 'stats')
        );
    }

    public function approve(Employer $employer)
    {
        $employer->update(['status' => 'approved']);
        return back()->with('success', 'Employer approved successfully.');
    }

    public function reject(Request $request, Employer $employer)
    {
        $request->validate(['reason' => 'required|string|max:1000']);
        $employer->update(['status' => 'rejected', 'rejection_reason' => $request->reason]);
        return back()->with('success', 'Employer rejected.');
    }

    public function destroy(Employer $employer)
    {
        $employer->delete();

        return redirect()
            ->route('admin.employers.index')
            ->with('success', 'Employer deleted with all related records.');
    }
}
