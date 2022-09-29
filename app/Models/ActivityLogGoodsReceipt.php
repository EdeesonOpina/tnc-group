<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogGoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'device',
        'uri',
        'auth_id',
        'goods_receipt_id',
        'description',
        'status',
    ];

    public function auth()
    {
        return $this->hasOne(User::class, 'id', 'auth_id');
    }

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }
}
