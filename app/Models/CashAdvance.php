<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'user_id',
        'date_borrowed',
        'price',
        'reason',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
