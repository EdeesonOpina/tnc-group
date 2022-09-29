<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'person',
        'email',
        'phone',
        'mobile',
        'line_address_1',
        'line_address_2',
        'image',
        'status',
    ];
}
