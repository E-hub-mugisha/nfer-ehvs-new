<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Government;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GovernmentController extends Controller
{
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

        $governments = $query->latest()->paginate(10);

        return view('admin.government.index', compact('governments'));
    }

    /**
     * Normalize raw input before validation.
     */
    private function normalizeGovernmentInput(Request $request): void
    {
        $request->merge([
            'contact_email' => $request->contact_email ? strtolower(trim($request->contact_email)) : null,
            'name'          => $request->name ? trim($request->name) : null,
            'website'       => $request->website ? trim($request->website) : null,
        ]);
    }

    private function governmentMessages(): array
    {
        return [
            'name.min'              => 'Government name must be at least 2 characters.',
            'established_year.max'  => 'Established year cannot be in the future.',
            'contact_email.unique'  => 'This email is already registered to another government entity or user.',
            'website.regex'         => 'Website must start with http:// or https://.',
        ];
    }

    /**
     * Store a newly created government + linked user account.
     */
    public function store(Request $request)
    {
        $this->normalizeGovernmentInput($request);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255', 'min:2'],
            'country'          => ['required', 'string', 'max:100'],
            'government_type'  => ['required', Rule::in(['Ministry', 'Department', 'Agency', 'Authority'])],
            'established_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'contact_email'    => ['required', 'email:rfc,dns', 'max:255', 'unique:governments,contact_email', 'unique:users,email'],
            'website'          => ['required', 'url', 'max:255', 'regex:/^https?:\/\//'],
            'rdb_number'        => ['nullable', 'string', 'max:100'],
            'tin_number'        => ['nullable', 'string', 'max:100'],
        ], $this->governmentMessages());

        // Generate a random, human-readable password
        $plainPassword = Str::password(12);

        $government = DB::transaction(function () use ($validated, $plainPassword) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['contact_email'],
                'password' => Hash::make($plainPassword),
                'role'     => 'government', // matches isGovernment() check
            ]);

            return Government::create([
                ...$validated,
                'user_id' => $user->id,
            ]);
        });

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', "Government created successfully. Login email: {$government->contact_email} | Temporary password: {$plainPassword}");
    }

    public function show(Government $government)
    {
        $government->load('user', 'verifiedBy');
        return view('admin.government.show', compact('government'));
    }

    /**
     * Update the specified government AND keep its linked user in sync.
     */
    public function update(Request $request, Government $government)
    {
        $this->normalizeGovernmentInput($request);

        $validated = $request->validate([
            'name'             => ['sometimes', 'string', 'max:255', 'min:2'],
            'country'          => ['sometimes', 'string', 'max:100'],
            'government_type'  => ['sometimes', Rule::in(['Ministry', 'Department', 'Agency', 'Authority'])],
            'established_year' => ['sometimes', 'integer', 'min:1800', 'max:' . date('Y')],
            'contact_email'    => ['sometimes', 'email:rfc,dns', 'max:255', Rule::unique('governments', 'contact_email')->ignore($government->id)],
            'website'          => ['sometimes', 'url', 'max:255', 'regex:/^https?:\/\//'],
            'rdb_number'        => ['nullable', 'string', 'max:100'],
            'tin_number'        => ['nullable', 'string', 'max:100'],
        ], $this->governmentMessages());

        DB::transaction(function () use ($validated, $government) {
            $government->update($validated);

            // Keep the linked User record in sync (name + login email)
            if ($government->user) {
                $userUpdates = [];

                if (isset($validated['name'])) {
                    $userUpdates['name'] = $validated['name'];
                }

                if (isset($validated['contact_email']) && $validated['contact_email'] !== $government->user->email) {
                    $emailTaken = User::where('email', $validated['contact_email'])
                        ->where('id', '!=', $government->user->id)
                        ->exists();

                    if (!$emailTaken) {
                        $userUpdates['email'] = $validated['contact_email'];
                    }
                }

                if (!empty($userUpdates)) {
                    $government->user->update($userUpdates);
                }
            }
        });

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government updated successfully.');
    }

    public function destroy(Government $government)
    {
        $government->delete();

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government deleted successfully');
    }

    public function verify(Request $request, Government $government)
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            return redirect()
                ->route('admin.government.users.index')
                ->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'verification_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $government->verify($request->user(), $validated['verification_notes'] ?? null);

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government verified successfully');
    }

    public function unverify(Request $request, Government $government)
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            return redirect()
                ->route('admin.government.users.index')
                ->with('error', 'Unauthorized');
        }

        $government->unverify();

        return redirect()
            ->route('admin.government.users.index')
            ->with('success', 'Government unverified successfully');
    }

    public function verified()
    {
        $governments = Government::verified()->orderBy('name')->get();
        return view('admin.government.index', compact('governments'));
    }

    public function unverified()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('admin.government.users.index')->with('error', 'Unauthorized');
        }

        $governments = Government::unverified()->orderBy('created_at', 'desc')->get();
        return view('admin.government.index', compact('governments'));
    }
}