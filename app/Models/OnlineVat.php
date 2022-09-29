<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineVat extends Model
{
    use HasFactory;

    protected $table = 'online_vat';

    protected $fillable = [
        'so_number', // company receipt
        'payment_id',
        'cart_id',
        'price',
        'status',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }
}
