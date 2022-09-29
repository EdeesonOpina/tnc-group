<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank',
        'name',
        'number',
        'image',
        'status',
    ];

    const COD = 1;
    const PAYPAL = 2;
    const BDO = 3;
    const METROBANK = 4;
    const CHINABANK = 5;
}
