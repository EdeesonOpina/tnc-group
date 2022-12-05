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
    const CANCELLED = 9;
}
