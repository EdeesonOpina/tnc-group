<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'usd_rate',
        'slug',
        'company_id',
        'client_id',
        'name',
        'vat_rate',
        'has_usd',
        'margin',
        'usd_total',
        'internal_total',
        'total',
        'start_date',
        'end_date',
        'duration_date',
        'description',
        'asf',
        'usd_asf',
        'signed_ce',
        'usd_vat',
        'vat',
        'proposal_ownership',
        'confidentiality',
        'project_confirmation',
        'payment_terms',
        'validity',
        'prepared_by_user_id',
        'noted_by_user_id',
        'client_contact_id',
        'created_by_user_id',
        'noted_by_user_id',
        'approved_by_user_id',
        'remarks',
        'project_status',
        'conforme_signature',
        'approved_at',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function prepared_by_user()
    {
        return $this->hasOne(User::class, 'id', 'prepared_by_user_id');
    }

    public function client_contact()
    {
        return $this->hasOne(ClientContact::class, 'id', 'client_contact_id');
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

    public function used_cost()
    {
        $brf = BudgetRequestForm::where('project_id', $this->id)
                            ->where('status', '!=', BudgetRequestFormStatus::DISAPPROVED)
                            ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                            ->sum('total');

        return $brf;
    }
}
