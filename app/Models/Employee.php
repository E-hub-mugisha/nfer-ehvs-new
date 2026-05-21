<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nid',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'phone',
        'email',
        'photo',
        'district',
        'sector',
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

    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class);
    }
}
