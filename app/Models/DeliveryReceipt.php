<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'received_by_user_id',
        'delivery_receipt_number',
        'status',
    ];

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function received_by_user()
    {
        return $this->hasOne(User::class, 'id', 'received_by_user_id');
    }
}
