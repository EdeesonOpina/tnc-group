<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestFormStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ON_PROCESS = 1;
    const FOR_APPROVAL = 2;
    const FOR_FINAL_APPROVAL = 3;
    const APPROVED = 4;
    const DONE = 5;
    const DISAPPROVED = 6;
    const FOR_LIQUIDATION = 7;
    const LIQUIDATED = 8;
    const CANCELLED = 9;
    const FOR_PROCESS = 10;
    const FOR_RELEASE = 11;
    const RELEASED = 12;
    const FOR_BANK_DEPOSIT_SLIP = 13;
    const FOR_LIQUIDATION_BANK_DEPOSIT_SLIP = 14;
}
