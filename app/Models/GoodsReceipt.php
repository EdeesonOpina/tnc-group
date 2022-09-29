<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'delivery_receipt_number',
        'reference_number',
        'received_by_user_id',
        'created_by_user_id',
        'note',
        'status',
    ];

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }

    public function received_by_user()
    {
        return $this->hasOne(User::class, 'id', 'received_by_user_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }
}
