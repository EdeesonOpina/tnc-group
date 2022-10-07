<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\ProjectDetail;
use App\Models\ProjectDetailStatus;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class ProjectController extends Controller
{
    public function print_ce($project_id)
    {
        $project = Project::find($project_id);
        $project_details = ProjectDetail::where('project_id', $project_id)
                        ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project_id)
                        ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project_id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        return view('admin.projects.prints.ce', compact(
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }
}
