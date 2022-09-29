<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number', // company receipt
        'user_id',
        'branch_id',
        'salesperson_id',
        'authorized_user_id',
        'is_credit',
        'discount',
        'vat',
        'total',
        'real_total',
        'mop',
        'note',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function authorized_user()
    {
        return $this->hasOne(User::class, 'id', 'authorized_user_id');
    }

    public function salesperson()
    {
        return $this->hasOne(User::class, 'id', 'salesperson_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
