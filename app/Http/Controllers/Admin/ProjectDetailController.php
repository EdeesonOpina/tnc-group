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
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class ProjectDetailController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'internal_price' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/project/details', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/project/details/' . $image_name; // save the destination of the file to the database
        }

        $data['total'] = $request->qty * $request->price;
        $data['internal_total'] = $request->qty * $request->internal_price;
        $data['status'] = ProjectDetailStatus::FOR_APPROVAL; // if you want to insert to a specific column
        $project_detail = ProjectDetail::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.projects.manage', [$project_detail->project->id]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'internal_price' => 'required',
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

        $project_detail = ProjectDetail::find($request->project_detail_id);
        $data['internal_total'] = $request->internal_price * $request->qty;
        $data['total'] = $request->price * $request->qty;
        $project_detail->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function approve(Request $request, $project_detail_id)
    {
        $project_detail = ProjectDetail::find($project_detail_id);
        $project_detail->status = ProjectDetailStatus::APPROVED; // mark data as cancelled
        $project_detail->save();

        $project = Project::find($project_detail->project_id);
        $project->internal_total += $project_detail->internal_total;
        $project->total += $project_detail->total;
        $project->save();

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $project_detail_id)
    {
        $project_detail = ProjectDetail::find($project_detail_id);
        $project_detail->status = ProjectDetailStatus::DISAPPROVED; // mark data as cancelled
        $project_detail->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function recover(Request $request, $project_detail_id)
    {
        $project_detail = ProjectDetail::find($project_detail_id);
        $project_detail->status = ProjectDetailStatus::FOR_APPROVAL; // mark data as active
        $project_detail->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function cancel(Request $request, $project_detail_id)
    {
        $project_detail = ProjectDetail::find($project_detail_id);
        $project_detail->status = ProjectDetailStatus::CANCELLED; // mark data as cancelled
        $project_detail->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function delete(Request $request, $project_detail_id)
    {
        $project_detail = ProjectDetail::find($project_detail_id);
        $project_detail->status = ProjectDetailStatus::INACTIVE; // mark data as inactive
        $project_detail->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
