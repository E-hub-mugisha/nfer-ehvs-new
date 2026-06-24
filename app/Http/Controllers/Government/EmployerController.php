<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::latest()->paginate(10);

        return view(
            'government.employers.index',
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
            'government.employers.show',
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
        DB::transaction(function () use ($employer) {

            $employmentRecordIds = $employer->employmentRecords()->pluck('id');

            TransferRequest::whereIn(
                'current_employment_record_id',
                $employmentRecordIds
            )->delete();

            EmploymentRecord::whereIn('id', $employmentRecordIds)->delete();

            $employer->delete();
        });

        return redirect()->route('government.employers.index')->with('success', 'Employer deleted.');
    }

    public function update(Request $request, Employer $employer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:employers,email,' . $employer->id,
            'phone'        => 'nullable|string|max:20',
            'rdb_number'   => 'nullable|string|max:100',
            'tin_number'   => 'nullable|string|max:100',
            'address'      => 'nullable|string|max:500',
        ]);

        $employer->update($validated);

        return redirect()->back()->with('success', 'Employer details updated successfully.');
    }
}
