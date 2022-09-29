<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'goods_receipt_id',
        'date_issued',
        'date_released',
        'due_date',
        'mop',
        'check_number',
        'price',
        'image',
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
}
