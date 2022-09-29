<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'category_id');
    }
}
