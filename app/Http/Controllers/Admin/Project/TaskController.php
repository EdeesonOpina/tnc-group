<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectStatus;
use App\Models\ProjectTaskStatus;

class TaskController extends Controller
{
    public function show($project_id)
    {
        $project = Project::find($project_id);
        $project_tasks = ProjectTask::where('project_id', $project_id)
                    ->orderBy('created_at', 'desc')
                    ->where('status', '!=', ProjectTaskStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.projects.tasks.show', compact(
            'project_tasks',
            'project'
        ));
    }

    public function add($project_id)
    {
        $project = Project::find($project_id);

        return view('admin.projects.tasks.details.add', compact(
            'project',
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'description' => 'nullable',
            'file' => 'nullable|image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('file')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('file')->getClientOriginalExtension(); // set unique name for that file
            $request->file('file')->move('uploads/images/project/tasks', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/images/project/tasks/' . $image_name; // save the destination of the file to the database
        }

        $data['created_by_user_id'] = auth()->user()->id;
        $project_task = ProjectTask::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.projects.tasks', [$project_task->project->id]);
    }

    public function edit($project_id, $project_task_id)
    {
        $project = Project::find($project_id);
        $project_tasks = ProjectTask::find($project_task_id);

        return view('admin.projects.tasks.details.edit', compact(
            'project',
            'project_tasks',
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'description' => 'nullable',
            'file' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('file')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('file')->getClientOriginalExtension(); // set unique name for that file
            $request->file('file')->move('uploads/images/projects/tasks', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/images/projects/tasks/' . $image_name; // save the destination of the file to the database
        }

        $project_task = ProjectTask::find($request->project_task_id);
        $project_task->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.projects.tasks', [$project_task->project->id]);
    }
}
