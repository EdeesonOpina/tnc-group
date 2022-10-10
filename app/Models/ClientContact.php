<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'position',
        'email',
        'phone',
        'mobile',
        'line_address_1',
        'line_address_2',
        'status',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
