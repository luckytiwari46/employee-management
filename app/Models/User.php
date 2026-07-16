<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that can be mass-assigned.
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'age',
        'dob',
        'profile_picture',
        'role',
        'is_admin',
    ];

    /**
     * Attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting definitions.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash passwords when set.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // Only hash if not already hashed
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    /**
     * Relationships
     */
    // public function qualifications()
    // {
    //     return $this->hasMany(Qualification::class);
    // }

    // public function experiences()
    // {
    //     return $this->hasMany(Experience::class);
    // }

    // public function addresses()
    // {
    //     return $this->hasMany(Address::class);
    // }

    // /**
    //  * Check if user is admin.
    //  */
    // public function isAdmin()
    // {
    //     return $this->is_admin === 1 || $this->role === 'admin';
    // }
    public function qualifications()
{
    return $this->hasMany(\App\Models\Qualification::class, 'user_id');
}

public function experiences()
{
    return $this->hasMany(\App\Models\Experience::class, 'user_id');
}

public function addresses()
{
    return $this->hasMany(\App\Models\Address::class, 'user_id');
}

}
