<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const APPROVED = 1;
    const DISAPPROVED = 9;
}
