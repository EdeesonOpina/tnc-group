<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category_id',
        'sub_category_id',
        'name',
        'qty',
        'description',
        'internal_price',
        'price',
        'internal_total',
        'total',
    ];

    public function category()
    {
        return $this->hasOne(ProjectCategory::class, 'id', 'category_id');
    }

    public function sub_category()
    {
        return $this->hasOne(ProjectSubCategory::class, 'id', 'sub_category_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
