<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'image',
        'status',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'so_number', 'so_number');
    }
}
