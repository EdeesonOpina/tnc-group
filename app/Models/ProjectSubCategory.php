<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'status',
    ];

    public function category()
    {
        return $this->hasOne(ProjectCategory::class, 'id', 'category_id');
    }
}
