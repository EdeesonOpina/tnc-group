<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderStatus extends Model
{
    use HasFactory;

    const ON_PROCESS = 0;
    const READY_FOR_GRPO = 1;
    const CHECKING_FOR_GRPO = 2;
    const DONE = 3;
    const FOR_APPROVAL = 4;
    const APPROVED = 5;
    const DISAPPROVED = 6;
    const CANCELLED = 9;
}
