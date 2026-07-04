<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\EmploymentRecord;
use App\Models\TransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $request->validate(['verification_notes' => 'required|string|max:1000']);
        $employer->update(['status' => 'rejected', 'verification_notes' => $request->verification_notes]);
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
            'company_name' => ['required', 'string', 'max:255', 'regex:/^[\pL0-9\s\.\,\-\&]+$/u'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('employers', 'email')->ignore($employer->id)],
            'phone'        => ['nullable', 'string', 'regex:/^(\+?250|0)?7[0-9]{8}$/'],
            'rdb_number'   => [
                'nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('employers', 'rdb_number')->ignore($employer->id),
            ],
            'tin_number'   => [
                'nullable', 'string', 'regex:/^[0-9]{9}$/',
                Rule::unique('employers', 'tin_number')->ignore($employer->id),
            ],
            'address'      => ['nullable', 'string', 'max:500'],
        ], [
            'company_name.regex' => 'Company name may only contain letters, numbers, spaces, and . , - &.',
            'phone.regex'         => 'Please enter a valid Rwandan phone number (e.g. 078XXXXXXX or +2507XXXXXXXX).',
            'rdb_number.regex'    => 'RDB number may only contain letters and numbers.',
            'tin_number.regex'    => 'TIN number must be exactly 9 digits.',
        ]);

        $employer->update($validated);

        return redirect()->back()->with('success', 'Employer details updated successfully.');
    }
}