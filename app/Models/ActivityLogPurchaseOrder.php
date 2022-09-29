<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'device',
        'uri',
        'auth_id',
        'purchase_order_id',
        'description',
        'status',
    ];

    public function auth()
    {
        return $this->hasOne(User::class, 'id', 'auth_id');
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }
}
