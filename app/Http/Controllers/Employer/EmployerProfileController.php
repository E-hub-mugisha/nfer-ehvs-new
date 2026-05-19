<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Employer;

class EmployerProfileController extends Controller
{
    /**
     * Show the profile creation form (for new employers).
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->employer) {
            return redirect()->route('employer.profile.edit')
                ->with('info', 'Your profile already exists. You can edit it here.');
        }

        return view('employer.profile.create');
    }

    /**
     * Store a newly created employer profile.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'rdb_number'   => ['nullable', 'string', 'max:100', 'unique:employers,rdb_number'],
            'tin_number'   => ['nullable', 'string', 'max:100', 'unique:employers,tin_number'],
            'email'        => ['required', 'email', 'max:255', 'unique:employers,email'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:500'],
        ]);
        $validated['user_id'] = $user->id;
        $validated['status']  = 'pending';

        // create the employer record directly (avoid calling a missing employer() relation)
        Employer::create($validated);

        return redirect()->route('employer.dashboard')
            ->with('success', 'Your employer profile has been created successfully.');
    }

    /**
     * Show the edit form for the authenticated employer.
     */
    public function edit()
    {
        $user     = Auth::user();
        $employer = $user->employer;

        if (! $employer) {
            return redirect()->route('employer.profile.create')
                ->with('warning', 'Please create your employer profile first.');
        }

        return view('employer.profile.edit', compact('employer'));
    }

    /**
     * Update the authenticated employer's profile.
     */
    public function update(Request $request)
    {
        $user     = Auth::user();
        $employer = $user->employer;

        if (! $employer) {
            return redirect()->route('employer.profile.create')
                ->with('warning', 'Employer profile not found. Please create one first.');
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'rdb_number'   => [
                'nullable', 'string', 'max:100',
                Rule::unique('employers', 'rdb_number')->ignore($employer->id),
            ],
            'tin_number'   => [
                'nullable', 'string', 'max:100',
                Rule::unique('employers', 'tin_number')->ignore($employer->id),
            ],
            'email'        => [
                'required', 'email', 'max:255',
                Rule::unique('employers', 'email')->ignore($employer->id),
            ],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:500'],
        ]);

        $employer->update($validated);

        return redirect()->route('employer.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}