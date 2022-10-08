<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestFormStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const FOR_APPROVAL = 1;
    const APPROVED = 2;
    const DONE = 3;
    const DISAPPROVED = 4;
    const CANCELLED = 9;
}
