<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const CANCELLED = 9;
    const DISAPPROVED = 8;
    const FOR_APPROVAL = 1;
    const FOR_FINAL_APPROVAL = 2;
    const UNPAID = 3;
    const PARTIALLY_PAID = 4;
    const FULLY_PAID = 5;
}
