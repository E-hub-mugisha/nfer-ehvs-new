<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
        'company_name',
        'rdb_number',
        'tin_number',
        'email',
        'phone',
        'address',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employmentRecords()
    {
        return $this->hasMany(EmploymentRecord::class);
    }
}
