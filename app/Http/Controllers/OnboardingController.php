<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /**
     * Show the role-selection / profile-completion page.
     */
    public function show(): View
    {
        return view('onboarding.select-role');
    }

    /**
     * Store employee profile and assign role.
     */
    public function storeEmployee(Request $request): RedirectResponse
    {
        $request->validate([
            'nid'        => ['required', 'string', 'size:16', 'unique:employees,nid'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'gender'     => ['required', 'in:Male,Female'],
            'dob'        => ['required', 'date', 'before:today'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:255'],
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'district'   => ['nullable', 'string', 'max:255'],
            'sector'     => ['nullable', 'string', 'max:255'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
        }

        // Create employee profile linked to this user
        Employee::create([
            'user_id'    => $user->id,
            'nid'        => $request->nid,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'gender'     => $request->gender,
            'dob'        => $request->dob,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'photo'      => $photoPath ? 'storage/' . $photoPath : null,
            'district'   => $request->district,
            'sector'     => $request->sector,
        ]);

        // Update user role
        $user->role = 'employee';
        $user->save();

        return redirect()->route('employee.dashboard')
                         ->with('success', 'Profile completed! Welcome aboard.');
    }

    /**
     * Store employer profile and assign role.
     */
    public function storeEmployer(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'rdb_number'   => ['required', 'string', 'max:100', 'unique:employers,rdb_number'],
            'tin_number'   => ['required', 'string', 'max:100', 'unique:employers,tin_number'],
            'email'        => ['required', 'email', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'address'      => ['nullable', 'string', 'max:500'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Create employer profile linked to this user
        Employer::create([
            'user_id'      => $user->id,
            'company_name' => $request->company_name,
            'rdb_number'   => $request->rdb_number,
            'tin_number'   => $request->tin_number,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'status'       => 'pending', // awaiting admin approval
        ]);

        // Update user role
        $user->role = 'employer';
        $user->save();


        return redirect()->route('employer.dashboard')
                         ->with('success', 'Employer profile submitted! Your account is pending approval.');
    }

    /**
     * Assign government role (no profile model — just role update).
     */
    public function storeGovernment(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Update user role
        $user->role = 'government';
        $user->save();


        return redirect()->route('government.dashboard')
                         ->with('success', 'Government account registered. Awaiting admin verification.');
    }
}