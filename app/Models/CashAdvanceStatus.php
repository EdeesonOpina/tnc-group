<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const CANCELLED = 9;
    const UNPAID = 1;
    const PARTIALLY_PAID = 2;
    const FULLY_PAID = 3;
}
