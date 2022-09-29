<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogAuth extends Model
{
    use HasFactory;

    protected $table = 'activity_log_auth';

    protected $fillable = [
        'ip_address',
        'device',
        'uri',
        'auth_id',
        'description',
        'status',
    ];

    public function auth()
    {
        return $this->hasOne(User::class, 'id', 'auth_id');
    }
}
