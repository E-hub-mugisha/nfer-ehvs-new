<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // Helper methods for role checking
    public function isEmployee()
    {
        return $this->user_type === 'employee';
    }

    public function isEmployer()
    {
        return $this->user_type === 'employer';
    }

    public function isGovernment()
    {
        return $this->user_type === 'government';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function employer()
    {
        return $this->hasOne(Employer::class);
    }
}
