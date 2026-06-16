<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Government extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'government_type',
        'established_year',
        'contact_email',
        'website',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_notes',
        'user_id'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'established_year' => 'integer',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'verification_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the user who verified this government.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Mark government as verified.
     */
    public function verify(User $admin, ?string $notes = null): bool
    {
        return $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Mark government as unverified.
     */
    public function unverify(): bool
    {
        return $this->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
            'verification_notes' => null,
        ]);
    }

    /**
     * Scope to get only verified governments.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to get only unverified governments.
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    /**
     * Scope to filter by country.
     */
    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope to filter by government type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('government_type', $type);
    }
}
