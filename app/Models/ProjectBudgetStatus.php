<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBudgetStatus extends Model
{
    use HasFactory;

    const OVERBUDGET = 0;
    const WITHIN_BUDGET = 1;
}
