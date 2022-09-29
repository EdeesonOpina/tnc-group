<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_taken',
        'return_inventory_id',
        'supplier_id',
        'performed_by_user_id',
        'inventory_id',
        'qty',
        'type',
        'remarks',
        'status',
    ];

    public function return_inventory()
    {
        return $this->hasOne(ReturnInventory::class, 'id', 'return_inventory_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function performed_by_user()
    {
        return $this->hasOne(User::class, 'id', 'performed_by_user_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }
}
