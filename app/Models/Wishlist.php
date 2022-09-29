<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'package_id',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
