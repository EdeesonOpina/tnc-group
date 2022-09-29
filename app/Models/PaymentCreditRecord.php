<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCreditRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'bir_number',
        'mop',
        'payment_credit_id',
        'term',
        'price',
        'image',
        'status',
    ];

    public function payment_credit()
    {
        return $this->hasOne(PaymentCredit::class, 'id', 'payment_credit_id');
    }
}
