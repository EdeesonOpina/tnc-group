<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'inventory_id',
        'item_id',
        'user_id',
        'authorized_user_id',
        'price',
        'discount',
        'qty',
        'total',
        'checkout',
        'status',
    ];

    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function authorized_user()
    {
        return $this->hasOne(User::class, 'id', 'authorized_user_id');
    }
}
