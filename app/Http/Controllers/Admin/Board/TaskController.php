<?php

namespace App\Http\Controllers\Admin\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\BoardTask;
use App\Models\BoardTaskStatus;

class TaskController extends Controller
{
    public function show()
    {
        $board_tasks = BoardTask::orderBy('created_at', 'desc')
                    ->where('status', '!=', BoardTaskStatus::INACTIVE)
                    ->paginate(15);

        $total = BoardTask::where('status', '!=', BoardTaskStatus::INACTIVE)
                        ->count();
        $completed = BoardTask::where('status', BoardTaskStatus::DONE)
                        ->count();
        if (count($board_tasks) > 0) {
            $percentage = ($completed / $total) * 100;
        } else {
            $percentage = 0; 
        }

        /* last 30 days */
        $last_30_records = BoardTask::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(updated_at) as day_name"), \DB::raw("DAY(updated_at) as day"))
                    ->where('created_at', '>', Carbon::today()->subDay(30))
                    ->groupBy('day_name','day')
                    ->orderBy('day')
                    ->get();
          
        $last_30_days = [];
     
        foreach($last_30_records as $last_30_row) {
            $last_30_days['label'][] = $last_30_row->day_name;
            $last_30_days['data'][] = (int) $last_30_row->count;
        }

        /* set as json */
        $last_30_days_chart['chart_data'] = json_encode($last_30_days);

        return view('admin.boards.tasks.show', compact(
            'last_30_days_chart',
            'percentage',
            'total',
            'completed',
            'board_tasks',
        ));
    }

    public function add($board_id)
    {
        $board = Project::find($board_id);

        return view('admin.boards.tasks.details.add', compact(
            'project',
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'priority' => 'required',
            'assigned_to_user_id' => 'required',
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
            $request->file('file')->move('uploads/files/project/tasks', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/project/tasks/' . $image_name; // save the destination of the file to the database
        }

        /* check if completed */
        if ($request->status == BoardTaskStatus::DONE) {
            $data['completed_at'] = Carbon::now();
            $data['is_completed'] = 1;
        } else {
            $data['completed_at'] = null;
            $data['is_completed'] = 0;
        }

        $data['created_by_user_id'] = auth()->user()->id;
        $board_task = BoardTask::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return back();
    }

    public function edit($board_task_id)
    {
        $board_tasks = BoardTask::find($board_task_id);

        return view('admin.boards.tasks.details.edit', compact(
            'board_tasks',
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'priority' => 'required',
            'assigned_to_user_id' => 'required',
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
            $request->file('file')->move('uploads/files/projects/tasks', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/projects/tasks/' . $image_name; // save the destination of the file to the database
        }

        /* update new data */
        $board_task = BoardTask::find($request->board_task_id);
        $board_task->fill($data)->save();

        /* check if completed */
        if ($board_task->status == BoardTaskStatus::DONE) {
            $data['completed_at'] = Carbon::now();
            $data['is_completed'] = 1;
        } else {
            $data['completed_at'] = null;
            $data['is_completed'] = 0;
        }

        /* update data with correct status */
        $board_task->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function recover(Request $request, $board_task_id)
    {
        $board_task = BoardTask::find($board_task_id);
        $board_task->status = BoardTaskStatus::PENDING; // mark data as active
        $board_task->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function delete(Request $request, $board_task_id)
    {
        $board_task = BoardTask::find($board_task_id);
        $board_task->status = BoardTaskStatus::INACTIVE; // mark data as inactive
        $board_task->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
