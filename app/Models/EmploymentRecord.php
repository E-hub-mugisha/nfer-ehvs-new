<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'employer_id',
        'job_title',
        'department',
        'start_date',
        'end_date',
        'employment_status',
        'exit_reason',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }
}
