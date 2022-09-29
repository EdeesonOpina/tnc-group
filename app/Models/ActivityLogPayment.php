<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'device',
        'uri',
        'auth_id',
        'so_number',
        'description',
        'status',
    ];

    public function auth()
    {
        return $this->hasOne(User::class, 'id', 'auth_id');
    }
}
