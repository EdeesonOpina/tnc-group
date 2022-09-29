<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSVat extends Model
{
    use HasFactory;

    protected $table = 'pos_vat';

    protected $fillable = [
        'so_number', // company receipt
        'payment_id',
        'cart_id',
        'price',
        'payment_receipt_id',
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

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }
}
