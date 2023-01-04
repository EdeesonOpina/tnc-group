<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestFormFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_form_id',
        'remarks',
        'file',
        'remarks',
        'status',
    ];

    public function budget_request_form()
    {
        return $this->hasOne(BudgetRequestForm::class, 'id', 'budget_request_form_id');
    }
}
