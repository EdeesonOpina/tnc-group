<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const PENDING = 1;
    const ACTIVE = 2;
    const RESIGNED = 3;
    const DEACTIVATED = 9;
}
