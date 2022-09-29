<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayableStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const PENDING = 1;
    const PAID = 2;
    const UNPAID = 9;
}
