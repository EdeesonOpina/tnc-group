<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use AmrShawky\LaravelCurrency\Facade\Currency;
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
use App\Models\ClientContact;
use App\Models\ClientContactStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class ProjectController extends Controller
{
    public function show()
    {
        $projects = Project::orderBy('created_at', 'desc')
                    ->where('status', '!=', ProjectStatus::INACTIVE)
                    ->paginate(15);
        $users = User::orderBy('created_at', 'desc')
                    ->where('status', '!=', UserStatus::INACTIVE)
                    ->get();

        return view('admin.projects.show', compact(
            'users',
            'projects'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $client = $request->client ?? '*';
        $client_cost = $request->client_cost ?? '*';
        $prepared_by_user_id = $request->prepared_by_user_id ?? '*';
        $budget_status = $request->budget_status ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.projects.filter', [$reference_number, $client, $client_cost, $prepared_by_user_id, $budget_status, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $client, $client_cost, $prepared_by_user_id, $budget_status, $status, $from_date, $to_date)
    {
        $query = Project::leftJoin('clients', 'projects.client_id', '=', 'clients.id')
                    ->leftJoin('users', 'projects.prepared_by_user_id', '=', 'users.id')
                    ->select('projects.*')
                    ->where('projects.status', '!=', ProjectStatus::INACTIVE)
                    ->orderBy('projects.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('projects.reference_number', $reference_number);
        }

        if ($client != '*') {
            $query->where('clients.name', 'LIKE', '%' . $client . '%');
        }

        if ($client_cost != '*') {
            if ($client_cost == 'Below 100k')
                $query->where('projects.total', '<', '100000');

            if ($client_cost == '100k - 499k')
                $query->whereBetween('projects.total', ['100000', '499999']);

            if ($client_cost == '500k - 1m')
                $query->whereBetween('projects.total', ['500000', '1000000']);

             if ($client_cost == '500k - 1m')
                $query->where('projects.total', '>', '1000000');
        }

        if ($prepared_by_user_id != '*') {
            $query->where('users.id', $prepared_by_user_id);
        }

        if ($budget_status != '*') {
            $query->where('projects.budget_status', $budget_status);
        }

        if ($status != '*') {
            $query->where('projects.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('projects.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $projects = $query->paginate(15);
        $users = User::orderBy('created_at', 'desc')
                    ->where('status', '!=', UserStatus::INACTIVE)
                    ->get();

        return view('admin.projects.show', compact(
            'users',
            'projects'
        ));
    }

    public function add()
    {
        $users = User::where('status', UserStatus::ACTIVE)
                        ->get();
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();
        $client_contacts = ClientContact::where('status', ClientContactStatus::ACTIVE)
                                    ->orderBy('name', 'asc')
                                    ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        $usd = Currency::convert()
        ->from('USD')
        ->to('PHP')
        ->get();

        return view('admin.projects.add', compact(
            'usd',
            'users',
            'client_contacts',
            'clients',
            'companies'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'client_contact_id' => 'required',
            'has_usd' => 'required',
            'company_id' => 'required',
            'prepared_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'margin' => 'required',
            'usd_rate' => 'required',
            'vat_rate' => 'required',
            'description' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $prj_count = str_replace('CE-', '', Project::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest tnc sequence then add 1

        $data = request()->all(); // get all request
        $data['slug'] = rand();
        $data['reference_number'] = 'CE-' . str_pad($prj_count, 8, '0', STR_PAD_LEFT);

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/projects', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/projects/' . $image_name; // save the destination of the file to the database
        }

        $data['created_by_user_id'] = auth()->user()->id;
        $data['status'] = ProjectStatus::ON_PROCESS; // if you want to insert to a specific column
        $project = Project::create($data); // create data in a model

        /* prepared by user */
        $name = $project->prepared_by_user->firstname . ' ' . $project->prepared_by_user->lastname;
        $email = $project->prepared_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' created a project';

        /* send mail to user */
        // Mail::send('emails.projects.create', [
        //     'project' => $project
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function view($reference_number)
    {
        $project = Project::where('reference_number', $reference_number)->first();
        $project_details = ProjectDetail::where('project_id', $project->id)
                        ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                        ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project->id)
                        ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                        ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project->id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $usd_grand_total = $project->usd_total + $project->usd_vat + $project->usd_asf;
        // $internal_grand_total = $project->internal_total + $project->asf;
        $internal_grand_total = $project->internal_total;
        $project_margin = ($project->total - $internal_grand_total) + $project->asf;

        return view('admin.projects.view', compact(
            'project_margin',
            'grand_total',
            'usd_grand_total',
            'internal_grand_total',
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }

    public function manage($project_id)
    {
        $project = Project::find($project_id);
        $project_details = ProjectDetail::where('project_id', $project_id)
                                ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                ->paginate(15);
        $budget_request_forms = BudgetRequestForm::where('project_id', $project_id)
                        ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                        ->paginate(15);

        $budget_request_forms_total = BudgetRequestForm::where('project_id', $project_id)
                                            ->where('status', BudgetRequestFormStatus::APPROVED)
                                            ->sum('total');

        $grand_total = $project->total + $project->vat + $project->asf;
        $usd_grand_total = $project->usd_total + $project->usd_vat + $project->usd_asf;
        // $internal_grand_total = $project->internal_total + $project->asf;
        $internal_grand_total = $project->internal_total;
        $project_margin = ($project->total - $internal_grand_total) + $project->asf;

        return view('admin.projects.manage', compact(
            'project_margin',
            'grand_total',
            'usd_grand_total',
            'internal_grand_total',
            'budget_request_forms_total',
            'budget_request_forms',
            'project_details',
            'project'
        ));
    }

    public function edit($project_id)
    {
        $project = Project::find($project_id);
        $users = User::where('status', UserStatus::ACTIVE)
                        ->get();
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();
        $client_contacts = ClientContact::where('status', ClientContactStatus::ACTIVE)
                                    ->orderBy('name', 'asc')
                                    ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        $usd = Currency::convert()
        ->from('USD')
        ->to('PHP')
        ->get();

        return view('admin.projects.edit', compact(
            'usd',
            'users',
            'client_contacts',
            'clients',
            'companies',
            'project'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'prepared_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
            'client_contact_id' => 'required',
            'description' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/projects', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/projects/' . $image_name; // save the destination of the file to the database
        }

        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function signed_ce(Request $request)
    {
        $rules = [
            'signed_ce' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('signed_ce')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('signed_ce')->getClientOriginalExtension(); // set unique name for that file
            $request->file('signed_ce')->move('uploads/images/projects/signed-ce', $image_name); // move the file to the laravel project
            $data['signed_ce'] = 'uploads/images/projects/signed-ce/' . $image_name; // save the destination of the file to the database
        }

        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        /* prepared by user */
        $name = $project->prepared_by_user->firstname . ' ' . $project->prepared_by_user->lastname;
        $email = $project->prepared_by_user->email;
        $subject = $project->prepared_by_user->name . ' has signed the project';

        /* send mail to user */
        // Mail::send('emails.projects.upload-conforme', [
        //     'project' => $project
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function terms(Request $request)
    {
        $rules = [
            'proposal_ownership' => 'required',
            'confidentiality' => 'required',
            'project_confirmation' => 'required',
            'payment_terms' => 'required',
            'validity' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function has_usd(Request $request)
    {
        $rules = [
            'has_usd' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function asf(Request $request)
    {
        $rules = [
            'asf' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $usd_rate = Currency::convert()
        ->from('USD')
        ->to('PHP')
        ->get();

        $data = $request->all();
        $project = Project::find($request->project_id);

        if ($project->margin > 0) {
            $data['usd_asf'] = $request->asf / $usd_rate;
        }
        
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function start_date(Request $request)
    {
        $rules = [
            'start_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function end_date(Request $request)
    {
        $rules = [
            'end_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function duration_date(Request $request)
    {
        $rules = [
            'duration_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function vat(Request $request)
    {
        $rules = [
            'vat' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $usd_rate = Currency::convert()
        ->from('USD')
        ->to('PHP')
        ->get();

        $data = $request->all();
        $data['usd_vat'] = $request->vat / $usd_rate;
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function margin(Request $request)
    {
        $rules = [
            'margin' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $margin_price = ($project->total * ($project->margin / 100));
        $project->asf = $margin_price;
        $project->usd_asf = $margin_price / $project->usd_rate;
        $project->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function vat_rate(Request $request)
    {
        $rules = [
            'vat_rate' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $vat_price = (($project->total + $project->asf) * ($project->vat_rate / 100));
        $project->vat = $vat_price;
        $project->usd_vat = $vat_price / $project->usd_rate;
        $project->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function usd_rate(Request $request)
    {
        $rules = [
            'usd_rate' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $project = Project::find($request->project_id);
        $project->fill($data)->save();

        $pjds = ProjectDetail::where('project_id', $project->id)
                        ->get();

        foreach ($pjds as $pjd) {
            $pjd = ProjectDetail::find($pjd->id);
            $pjd->usd_price = $pjd->price / $pjd->project->usd_rate;
            $pjd->usd_total = ($pjd->qty * $pjd->price) / $pjd->project->usd_rate;
            $pjd->save();
        }

        $project->usd_asf = $project->asf / $project->usd_rate;
        $project->usd_total = $project->total / $project->usd_rate;
        $project->usd_vat = $project->vat / $project->usd_rate;
        $project->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.manage', [$project->id]);
    }

    public function approve(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::APPROVED; // mark data as cancelled
        $project->save();

        /* prepared by user */
        $name = $project->prepared_by_user->firstname . ' ' . $project->prepared_by_user->lastname;
        $email = $project->prepared_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' approved your project';

        /* send mail to user */
        // Mail::send('emails.projects.approve', [
        //     'project' => $project
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been approved');
        return redirect()->route('internals.projects.view', [$project->reference_number]);
    }

    public function disapprove(Request $request)
    {
        $project = Project::find($request->project_id);
        $project->remarks = $request->remarks;
        $project->status = ProjectStatus::DISAPPROVED; // mark data as cancelled
        $project->save();

        /* prepared by user */
        $name = $project->prepared_by_user->firstname . ' ' . $project->prepared_by_user->lastname;
        $email = $project->prepared_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' disapproved your project';

        /* send mail to user */
        // Mail::send('emails.projects.disapprove', [
        //     'project' => $project
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been disapproved');
        return redirect()->route('internals.projects.view', [$project->reference_number]);
    }

    public function for_approval(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::FOR_APPROVAL; // mark data as for approval
        $project->save();

        /* noted by user */
        $name = $project->noted_by_user->firstname . ' ' . $project->noted_by_user->lastname;
        $email = $project->noted_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' sent a project for approval';

        /* send mail to user */
        // Mail::send('emails.projects.create-for-approver', [
        //     'project' => $project
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been sent for approval');
        return back();
    }

    public function recover(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::ON_PROCESS; // mark data as active
        $project->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function done(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::DONE; // mark data as done
        $project->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function open_for_editing(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::OPEN_FOR_EDITING; // mark data as open for editing
        $project->save();

        $request->session()->flash('success', 'Data has been marked as open for editing');
        return back();
    }

    public function cancel(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::CANCELLED; // mark data as cancelled
        $project->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function delete(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::INACTIVE; // mark data as inactive
        $project->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
