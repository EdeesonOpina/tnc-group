<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SupplyStatus;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'item_id',
        'price',
        'status',
    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'id', 'item_id');
    }
}
