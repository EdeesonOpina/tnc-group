<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckVoucherRemark extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_voucher_id',
        'account_id',
        'cheque_number',
        'currency',
        'amount',
        'status',
    ];

    public function check_voucher()
    {
        return $this->hasOne(CheckVoucher::class, 'id', 'check_voucher_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
}
