<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildItem extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'ip_address',
        'inventory_id',
        'user_id',
        'price',
        'discount',
        'qty',
        'total',
        'status',
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
