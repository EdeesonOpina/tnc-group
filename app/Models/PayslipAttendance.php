<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipAttendance extends Model
{
    use HasFactory;

    protected $table = 'payslip_attendance';

    protected $fillable = [
        'type',
        'user_id',
        'time_in',
        'time_out',
        'hours_rendered',
        'price_per_hour',
        'total',
        'image',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
