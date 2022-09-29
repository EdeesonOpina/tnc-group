<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'user_id',
        'authorized_user_id',
        'action_taken',
        'serial_number_notes',
        'mop',
        'name',
        'price',
        'qty',
        'total',
        'remarks',
        'status',
    ];

    public function service_order()
    {
        return $this->hasOne(ServiceOrder::class, 'id', 'service_order_id');
    }

    public function authorized_user()
    {
        return $this->hasOne(User::class, 'id', 'authorized_user_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
