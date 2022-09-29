<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInventoryItemSerialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_serial_number_id',
        'return_inventory_id',
        'return_inventory_item_id',
        'status',
    ];

    public function item_serial_number()
    {
        return $this->hasOne(ItemSerialNumber::class, 'id', 'item_serial_number_id');
    }

    public function return_inventory()
    {
        return $this->hasOne(ReturnInventory::class, 'id', 'return_inventory_id');
    }

    public function return_inventory_item()
    {
        return $this->hasOne(ReturnInventoryItem::class, 'id', 'return_inventory_item_id');
    }
}
