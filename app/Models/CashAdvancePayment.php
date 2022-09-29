<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_advance_id',
        'price',
        'status',
    ];

    public function cash_advance()
    {
        return $this->hasOne(CashAdvance::class, 'id', 'cash_advance_id');
    }
}
