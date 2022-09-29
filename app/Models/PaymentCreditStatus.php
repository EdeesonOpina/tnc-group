<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCreditStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const PARTIALLY_PAID = 1;
    const FULLY_PAID = 2;
    const UNPAID = 6;
    const PENDING = 7;
    const OVERDUE = 8;
    const CREDIT = 9;
}
