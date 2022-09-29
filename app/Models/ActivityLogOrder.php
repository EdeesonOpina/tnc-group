<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'device',
        'uri',
        'auth_id',
        'order_id',
        'description',
        'status',
    ];

    public function auth()
    {
        return $this->hasOne(User::class, 'id', 'auth_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
