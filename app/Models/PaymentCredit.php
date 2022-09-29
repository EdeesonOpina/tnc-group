<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'invoice_number',
        'payment_receipt_id',
        'payment_id',
        'price',
        'interest',
        'days_due',
        'status',
    ];

    public function payment_receipt()
    {
        return $this->hasOne(PaymentReceipt::class, 'id', 'payment_receipt_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }
}
