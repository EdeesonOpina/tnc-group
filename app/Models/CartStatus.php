<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ACTIVE = 1;
    const CHECKED_OUT = 2;
}
