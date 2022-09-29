<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailNewsletterStatus extends Model
{
    use HasFactory;

    const INACTIVE = 0;
    const ACTIVE = 1;
}
