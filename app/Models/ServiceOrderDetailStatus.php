<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderDetailStatus extends Model
{
    use HasFactory;

    const PENDING = 0;
    const CONFIRMED = 1;
    const BACK_JOB = 2;
    const COMPLETED = 3;
    const FOR_REPAIR = 8;
    const FOR_RELEASE = 7;
    const CANCELLED = 9;
    const INACTIVE = 10;
}
