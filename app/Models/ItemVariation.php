<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_inventory_id',
        'child_inventory_id',
        'status',
    ];

    public function parent_inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'parent_inventory_id');
    }

    public function child_inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'child_inventory_id');
    }
}
