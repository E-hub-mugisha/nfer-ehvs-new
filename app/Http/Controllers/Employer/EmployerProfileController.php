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
     * Shared validation rules for employer profile fields.
     */
    private function rules(?int $employerId = null): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', 'regex:/^[\pL0-9\s\.\,\-\&]+$/u'],

            'rdb_number' => [
                'nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/',
                $employerId
                    ? Rule::unique('employers', 'rdb_number')->ignore($employerId)
                    : 'unique:employers,rdb_number',
            ],

            'tin_number' => [
                'nullable', 'string', 'regex:/^[0-9]{9}$/',
                $employerId
                    ? Rule::unique('employers', 'tin_number')->ignore($employerId)
                    : 'unique:employers,tin_number',
            ],

            'email' => [
                'required', 'email', 'max:255',
                $employerId
                    ? Rule::unique('employers', 'email')->ignore($employerId)
                    : 'unique:employers,email',
            ],

            'phone' => ['nullable', 'string', 'regex:/^(\+?250|0)?7[0-9]{8}$/'],

            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Shared custom messages for employer profile validation.
     */
    private function messages(): array
    {
        return [
            'company_name.regex' => 'Company name may only contain letters, numbers, spaces, and . , - &.',
            'rdb_number.regex'    => 'RDB number may only contain letters and numbers.',
            'tin_number.regex'    => 'TIN number must be exactly 9 digits.',
            'phone.regex'         => 'Please enter a valid Rwandan phone number (e.g. 078XXXXXXX or +2507XXXXXXXX).',
        ];
    }

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

        $validated = $request->validate($this->rules(), $this->messages());

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

        $validated = $request->validate($this->rules($employer->id), $this->messages());

        $employer->update($validated);

        return redirect()->route('employer.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}