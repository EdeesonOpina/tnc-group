<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckVoucherDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_voucher_id',
        'project_id',
        'name',
        'qty',
        'description',
        'remarks',
        'price',
        'file',
        'total',
    ];

    public function check_voucher()
    {
        return $this->hasOne(CheckVoucher::class, 'id', 'check_voucher_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
