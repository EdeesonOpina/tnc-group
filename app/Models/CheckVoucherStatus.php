<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckVoucherStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const DONE = 1;
    const ON_PROCESS = 2;
    const OPEN_FOR_EDITING = 3;
}
