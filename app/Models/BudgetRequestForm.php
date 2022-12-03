<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'needed_date',
        'name',
        'remarks',
        'payment_for_user_id',
        'payment_for_supplier_id',
        'checked_by_user_id',
        'requested_by_user_id',
        'noted_by_user_id',
        'project_id',
        'description',
        'price',
        'total',
    ];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function payment_for_user()
    {
        return $this->hasOne(User::class, 'id', 'payment_for_user_id');
    }

    public function payment_for_supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'payment_for_supplier_id');
    }

    public function checked_by_user()
    {
        return $this->hasOne(User::class, 'id', 'checked_by_user_id');
    }

    public function requested_by_user()
    {
        return $this->hasOne(User::class, 'id', 'requested_by_user_id');
    }

    public function noted_by_user()
    {
        return $this->hasOne(User::class, 'id', 'noted_by_user_id');
    }
}
