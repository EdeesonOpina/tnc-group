<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'company_id',
        'item_id',
        'qty',
        'agent_price',
        'price',
        'discount_from_date',
        'discount_to_date',
        'discount',
        'barcode',
        'points',
        'views',
        'status',
    ];

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function company()
    {
        return $this->hasOne(Branch::class, 'id', 'company_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
