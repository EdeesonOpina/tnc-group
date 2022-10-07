<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
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
