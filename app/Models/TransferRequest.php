<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'requesting_employer_id',
        'current_employer_id',
        'current_employment_record_id',
        'proposed_job_title',
        'proposed_department',
        'proposed_start_date',
        'reason',
        'status',
        'rejection_reason',
        'responded_at',
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'responded_at'        => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function requestingEmployer()
    {
        return $this->belongsTo(Employer::class, 'requesting_employer_id');
    }

    public function currentEmployer()
    {
        return $this->belongsTo(Employer::class, 'current_employer_id');
    }

    public function currentEmploymentRecord()
    {
        return $this->belongsTo(EmploymentRecord::class, 'current_employment_record_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
