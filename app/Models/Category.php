<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const COMPONENTS = 3;
    const PERIPHERALS = 4;
    const GAMING_CONSOLES = 5;
    const SOFTWARES = 6;
    const SECURITY = 7;
    const ACCESSORIES = 8;
    const APPAREL = 9;

    protected $fillable = [
        'name',
        'description',
        'is_package',
        'sort_order',
        'image',
        'status',
    ];
}
