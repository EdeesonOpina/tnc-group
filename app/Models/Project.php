<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'name',
        'cost',
        'end_date',
        'description',
        'created_by_user_id',
        'noted_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }

    public function noted_by_user()
    {
        return $this->hasOne(User::class, 'id', 'noted_by_user_id');
    }

    public function approved_by_user()
    {
        return $this->hasOne(User::class, 'id', 'approved_by_user_id');
    }
}
