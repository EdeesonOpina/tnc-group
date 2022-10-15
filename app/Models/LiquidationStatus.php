<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidationStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const FOR_APPROVAL = 1;
    const APPROVED = 2;
    const DISAPPROVED = 3;
}
