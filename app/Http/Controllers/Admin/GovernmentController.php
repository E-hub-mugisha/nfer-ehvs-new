<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Government;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;

class GovernmentController extends Controller
{
    /**
     * Display a listing of all governments.
     */
    public function index(Request $request)
    {
        $query = Government::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('country', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('government_type', $request->type);
        }

        if ($request->status === 'verified') {
            $query->where('is_verified', true);
        }

        if ($request->status === 'unverified') {
            $query->where('is_verified', false);
        }

        $governments = $query
            ->latest()
            ->paginate(10);

        return view('admin.government.index', compact('governments'));
    }

    /**
     * Store a newly created government in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'government_type' => 'required|in:Ministry,Department,Agency,Authority',
            'established_year' => 'required|integer|min:1800|max:' . date('Y'),
            'contact_email' => 'required|email|unique:governments,contact_email',
            'website' => 'required|url',
        ]);

        $government = Government::create($validated);

        return redirect()->route('admin.governments.index')->with('success', 'Government created successfully.');
    }

    /**
     * Display the specified government.
     */
    public function show(Government $government)
    {
        $government->load('user', 'verifiedBy');
        return view('admin.government.show', compact('government'));
    }


    /**
     * Update the specified government in storage.
     */
    public function update(Request $request, Government $government)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:100',
            'government_type' => 'sometimes|in:Ministry,Department,Agency,Authority',
            'established_year' => 'sometimes|integer|min:1800|max:' . date('Y'),
            'contact_email' => 'sometimes|email|unique:governments,contact_email,' . $government->id,
            'website' => 'sometimes|url',
        ]);

        $government->update($validated);

        return redirect()->route('admin.governments.index')->with('success', 'Government updated successfully.');
    }

    /**
     * Delete the specified government from storage.
     */
    public function destroy(Government $government)
    {
        $government->delete();

        return redirect()->route('admin.governments.index')->with('success', 'Government deleted successfully');
    }

    /**
     * Verify a government (admin only).
     */
    public function verify(Request $request, Government $government)
    {
        // Check if user is admin
        if (!$request->user() || $request->user()->role !== 'admin') {
            return redirect()
                ->route('admin.government.users.index')
                ->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'verification_notes' => 'nullable|string|max:500',
        ]);

        $government->verify(
            $request->user(),
            $validated['verification_notes'] ?? null
        );

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government verified successfully');
    }

    public function unverify(Request $request, Government $government)
    {
        // Check if user is admin
        if (!$request->user() || $request->user()->role !== 'admin') {
            return redirect()
                ->route('admin.government.users.index')
                ->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'verification_notes' => 'nullable|string|max:500',
        ]);

        $government->unverify();

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government unverified successfully');
    }

    /**
     * Get list of verified governments only.
     */
    public function verified()
    {
        $governments = Government::verified()->orderBy('name')->get();

        return view('admin.government.index', compact('governments'));
    }

    /**
     * Get list of unverified governments only.
     */
    public function unverified()
    {
        // Check if user is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('admin.governments.index')->with('error', 'Unauthorized');
        }

        $governments = Government::unverified()->orderBy('created_at', 'desc')->get();

        return view('admin.government.index', compact('governments'));
    }
}
