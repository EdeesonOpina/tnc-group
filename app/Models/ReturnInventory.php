<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_receipt_number',
        'branch_id',
        'supplier_id',
        'goods_receipt_id',
        'payment_receipt_id',
        'reference_number',
        'note',
        'created_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'status',
    ];

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function payment_receipt()
    {
        return $this->hasOne(PaymentReceipt::class, 'id', 'payment_receipt_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }

    public function approved_by_user()
    {
        return $this->hasOne(User::class, 'id', 'approved_by_user_id');
    }
}
