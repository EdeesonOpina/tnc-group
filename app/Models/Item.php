<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'item_code',
        'name',
        'category_id',
        'sub_category_id',
        'brand_id',
        'unit',
        'description',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function sub_category()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function supplies(): BelongsTo
    {
        return $this->hasMany(Supply::class);
    }
}
