<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_form_id',
        'category_id',
        'cost',
        'description',
        'date',
        'name',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->hasOne(LiquidationCategory::class, 'id', 'category_id');
    }

    public function budget_request_form()
    {
        return $this->hasOne(BudgetRequestForm::class, 'id', 'budget_request_form_id');
    }
}
