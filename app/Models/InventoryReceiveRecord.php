<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceiveRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'inventory_id',
        'user_id',
        'qty',
        'free_qty',
        'price',
        'discount',
        'total',
    ];

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
