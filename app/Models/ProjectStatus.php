<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ON_PROCESS = 1;
    const DONE = 3;
    const FOR_APPROVAL = 4;
    const APPROVED = 5;
    const DISAPPROVED = 6;
    const CANCELLED = 9;
}
