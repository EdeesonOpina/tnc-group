<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assigned_to_user_id',
        'created_by_user_id',
        'name',
        'description',
        'file',
        'priority',
        'is_completed',
        'status',
    ];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function assigned_to_user()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to_user_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }
}
