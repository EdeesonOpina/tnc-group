<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSerialNumberStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const AVAILABLE = 1;
    const FOR_CHECKOUT = 2;
    const SOLD = 3;
    const FLOATING = 9;
}
