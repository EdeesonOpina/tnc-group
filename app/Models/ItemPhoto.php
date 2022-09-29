<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'item_id',
        'image',
        'status',
    ];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
