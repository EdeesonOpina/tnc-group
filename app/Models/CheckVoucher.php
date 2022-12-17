<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_form_id',
        'prepared_by_user_id',
        'reference_number',
        'status',
    ];

    public function budget_request_form()
    {
        return $this->hasOne(BudgetRequestForm::class, 'id', 'budget_request_form_id');
    }

    public function prepared_by_user()
    {
        return $this->hasOne(User::class, 'id', 'prepared_by_user_id');
    }
}
