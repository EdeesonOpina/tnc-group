<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInventoryStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const FOR_WARRANTY = 1;
    const WAITING = 2;
    const FOR_RELEASE = 3;
    const CLEARED = 4;
    const OUT_OF_WARRANTY = 8;
    const CANCELLED = 9;
    const ON_PROCESS = 10;
}
