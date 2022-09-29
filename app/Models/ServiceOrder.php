<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'jo_number',
        'authorized_user_id',
        'date_in',
        'date_out',
        'branch_id',
        'user_id',
        'mop',
        'total',
        'remarks',
        'back_job_notes',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function authorized_user()
    {
        return $this->hasOne(User::class, 'id', 'authorized_user_id');
    }
}
