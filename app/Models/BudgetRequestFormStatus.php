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
    const APPROVED = 3;
    const DONE = 4;
    const DISAPPROVED = 5;
    const CANCELLED = 9;
}
