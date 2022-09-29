<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'person',
        'phone',
        'mobile',
        'image',
        'line_address_1',
        'line_address_2',
        'status',
    ];
}
