<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardTaskStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ACTIVE = 1;
    const PENDING = 2;
    const ON_PROGRESS = 3;
    const NEED_MORE_INFO = 4;
    const DONE = 5;
    const CANCELLED = 6;
    const TBD = 9;
}
