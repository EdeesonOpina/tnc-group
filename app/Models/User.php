<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'company',
        'email',
        'password',
        'birthdate',
        'phone',
        'mobile',
        'country_id',
        'region_id',
        'province_id',
        'city_id',
        'barangay_id',
        'line_address_1',
        'line_address_2',
        'branch_id',
        'role',
        'provider',
        'oauth_id',
        'avatar',
        'signature',
        'points',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
