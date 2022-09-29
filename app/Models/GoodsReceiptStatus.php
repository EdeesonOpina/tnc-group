<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptStatus extends Model
{
    use HasFactory;

    const FULFILLING = 1;
    const CLEARED = 2;
    const CANCELLED = 3;
}
