<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'reference_number',
        'note',
        'created_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }

    public function approved_by_user()
    {
        return $this->hasOne(User::class, 'id', 'approved_by_user_id');
    }
}
