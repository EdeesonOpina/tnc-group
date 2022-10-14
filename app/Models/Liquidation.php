<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'cost',
        'description',
        'date',
        'name',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->hasOne(LiquidationCategory::class, 'id', 'category_id');
    }
}
