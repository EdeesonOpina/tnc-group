<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        return redirect()->route('admin.projects.filter', [$name, $status, $from_date, $to_date])->withInput();
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
        return view('admin.projects.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
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

        $data['status'] = ProjectStatus::ACTIVE; // if you want to insert to a specific column
        Project::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.projects');
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
            'name' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
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

        return redirect()->route('admin.projects');
    }

    public function recover(Request $request, $project_id)
    {
        $project = Project::find($project_id);
        $project->status = ProjectStatus::ACTIVE; // mark data as active
        $project->save();

        $request->session()->flash('success', 'Data has been recovered');

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
