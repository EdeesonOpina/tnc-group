<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceiptStatus extends Model
{
    use HasFactory;

    const PENDING = 0;
    const CONFIRMED = 1;
    const FOR_DELIVERY = 2;
    const DELIVERED = 3;
    const CANCELLED = 9;
    const INACTIVE = 10;
}
