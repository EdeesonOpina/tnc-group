<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'company_id',
        'description',
        'note',
        'date',
        'price',
        'image',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(ExpenseCompany::class, 'id', 'company_id');
    }

    public function category()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'category_id');
    }
}
