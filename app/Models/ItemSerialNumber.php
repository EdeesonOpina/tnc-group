<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSerialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'cart_id',
        'payment_receipt_id',
        'goods_receipt_id',
        'delivery_receipt_id',
        'return_inventory_id',
        'payment_id',
        'code',
        'status',
    ];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function delivery_receipt()
    {
        return $this->hasOne(DeliveryReceipt::class, 'id', 'delivery_receipt_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

    public function items() 
    {
        return $this->hasMany(Item::class, 'item_id');
    }
}
