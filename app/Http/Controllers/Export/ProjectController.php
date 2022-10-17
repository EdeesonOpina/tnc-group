<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
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
use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class ProjectController extends Controller
{
    public function excel($project_id)
    {
        $project = Project::find($project_id);

        return Excel::download(new ProjectExport($project->id), $project->reference_number . '.xlsx');
    }

    public function print_ce($reference_number)
    {
        $project = Project::where('reference_number', $reference_number)->first();
        $project_details = ProjectDetail::where('project_id', $project->id)
                                ->where('status', ProjectDetailStatus::APPROVED)
                                ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project->id)
                        ->where('status', BudgetRequestForm::APPROVED)
                        ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project->id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $internal_grand_total = $project->internal_total + $project->asf;

        return view('admin.projects.prints.ce', compact(
            'grand_total',
            'internal_grand_total',
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }

    public function print_internal_ce($reference_number)
    {
        $project = Project::where('reference_number', $reference_number)->first();
        $project_details = ProjectDetail::where('project_id', $project->id)
                                ->where('status', ProjectDetailStatus::APPROVED)
                                ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project->id)
                                    ->where('status', BudgetRequestFormStatus::APPROVED)
                                    ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project->id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $internal_grand_total = $project->internal_total + $project->asf;

        return view('admin.projects.prints.internal-ce', compact(
            'grand_total',
            'internal_grand_total',
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }

    public function pdf_ce($project_id)
    {
        $project = Project::find($project_id);
        $project_details = ProjectDetail::where('project_id', $project->id)
                                ->where('status', ProjectDetailStatus::APPROVED)
                                ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project->id)
                                    ->where('status', BudgetRequestFormStatus::APPROVED)
                                    ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project->id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $internal_grand_total = $project->internal_total + $project->asf;

        // share data to view
        view()->share('project', $project);
        view()->share('project_details', $project_details);
        view()->share('grand_total', $grand_total);
        view()->share('internal_grand_total', $internal_grand_total);

        $pdf = PDF::loadView('admin.projects.pdf.ce', compact([$project, $project_details, $grand_total, $internal_grand_total]));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($goods_receipt->reference_number . '.pdf'); // return as view
        return $pdf->download($project->reference_number . '.pdf'); // return as download actual file
    }
}
