<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\ProjectDetail;
use App\Models\ProjectDetailStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class ProjectController extends Controller
{
    public function show()
    {
        $projects = Project::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.projects.show', compact(
            'projects'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.projects.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Project::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $projects = $query->paginate(15);

        return view('admin.projects.show', compact(
            'projects'
        ));
    }

    public function add()
    {
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.projects.add', compact(
            'clients',
            'companies'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'cost' => 'required',
            'end_date' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/projects', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/projects/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ProjectStatus::FOR_APPROVAL; // if you want to insert to a specific column
        Project::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('internals.projects');
    }

    public function view($project_id)
    {
        $project = Project::find($project_id);

        return view('admin.projects.view', compact(
            'project'
        ));
    }

    public function manage($project_id)
    {
        $project = Project::find($project_id);
        $details = ProjectDetail::where('project_id', $project_id)
                        ->where('status', ProjectDetailStatus::ACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.projects.manage', compact(
            'details',
            'project'
        ));
    }

    public function edit($project_id)
    {
        $project = Project::find($project_id);

        return view('admin.projects.edit', compact(
            'project'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'cost' => 'required',
            'end_date' => 'required',
            'description' => 'required',
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

        return redirect()->route('internals.projects');
    }

    public function recover(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::FOR_APPROVAL; // mark data as active
        $project->save();

        $request->session()->flash('success', 'Data has been recovered');

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
