<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'goods_receipt_id',
        'performed_by_user_id',
        'supply_id',
        'item_id',
        'qty',
        'free_qty',
        'received_qty',
        'discount',
        'price',
        'total',
        'note',
        'status',
    ];

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function performed_by_user()
    {
        return $this->hasOne(User::class, 'id', 'performed_by_user_id');
    }

    public function supply()
    {
        return $this->hasOne(Supply::class, 'id', 'supply_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
