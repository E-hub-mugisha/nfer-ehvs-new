<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'employee_id',
        'employment_record_id',
        'description',
        'evidence',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employmentRecord()
    {
        return $this->belongsTo(EmploymentRecord::class);
    }
}
