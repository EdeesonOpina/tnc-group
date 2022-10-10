<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'company_id',
        'category_id',
        'client_id',
        'name',
        'total',
        'end_date',
        'description',
        'asf',
        'vat',
        'proposal_ownership',
        'confidentiality',
        'project_confirmation',
        'payment_terms',
        'validity',
        'prepared_by_user_id',
        'noted_by_user_id',
        'conforme_by_user_id',
        'created_by_user_id',
        'noted_by_user_id',
        'approved_by_user_id',
        'approved_at',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function category()
    {
        return $this->hasOne(ProjectCategory::class, 'id', 'category_id');
    }

    public function prepared_by_user()
    {
        return $this->hasOne(User::class, 'id', 'prepared_by_user_id');
    }

    public function conforme_by_user()
    {
        return $this->hasOne(Client::class, 'id', 'conforme_by_user_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by_user_id');
    }

    public function noted_by_user()
    {
        return $this->hasOne(User::class, 'id', 'noted_by_user_id');
    }

    public function approved_by_user()
    {
        return $this->hasOne(User::class, 'id', 'approved_by_user_id');
    }
}
