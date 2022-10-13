<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckVoucherStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const DONE = 1;
}
