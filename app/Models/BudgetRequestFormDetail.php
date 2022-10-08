<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestFormDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_form_id',
        'name',
        'qty',
        'description',
        'price',
        'total',
    ];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
