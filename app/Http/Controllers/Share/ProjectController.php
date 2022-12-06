<?php

namespace App\Http\Controllers\Share;

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
    public function view($slug, $reference_number)
    {
        $project = Project::where('reference_number', $reference_number)->first();
        $project_details = ProjectDetail::where('project_id', $project->id)
                                ->where('status', ProjectDetailStatus::APPROVED)
                                ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project->id)
                        ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project->id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $internal_grand_total = $project->internal_total + $project->asf;

        return view('share.projects.ce', compact(
            'grand_total',
            'internal_grand_total',
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }

    public function conforme_signature(Request $request)
    {
        $rules = [
            'conforme_signature' => 'required|image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('conforme_signature')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('conforme_signature')->getClientOriginalExtension(); // set unique name for that file
            $request->file('conforme_signature')->move('uploads/images/projects/conforme-signature', $image_name); // move the file to the laravel project
            $data['conforme_signature'] = 'uploads/images/projects/conforme-signature/' . $image_name; // save the destination of the file to the database
        }

        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        /* prepared by user */
        $name = $project->prepared_by_user->firstname . ' ' . $project->prepared_by_user->lastname;
        $email = $project->prepared_by_user->email;
        $subject = $project->prepared_by_user->name . ' has signed the project';

        /* send mail to user */
        Mail::send('emails.projects.upload-conforme', [
            'project' => $project
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }
}
