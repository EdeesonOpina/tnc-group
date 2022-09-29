<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'is_package',
        'sort_order',
        'image',
        'status',
    ];

    const MOTHERBOARD = 6;
    const PROCESSOR = 5;
    const MEMORY = 8;
    const POWER_SUPPLY = 9;
    const POWER_SUPPLY_SLEEVES = 26;
    const GRAPHICS_CARD = 7;
    const AIO = 25;
    const AIR_COOLING = 13;
    const AUX_FAN = 86;
    const MONITOR = 22;
    const KEYBOARD = 16;
    const MOUSE = 15;
    const HEADSET = 23;
    const MIC = 54;
    const CASING = 14;
    const STORAGE = 10;

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
