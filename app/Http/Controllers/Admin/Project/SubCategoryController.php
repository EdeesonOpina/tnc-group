<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\ProjectCategory;
use App\Models\ProjectSubCategory;
use App\Models\ProjectCategoryStatus;
use App\Models\ProjectSubCategoryStatus;

class SubCategoryController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/projects/sub-categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/projects/sub-categories/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ProjectSubCategoryStatus::ACTIVE; // if you want to insert to a specific column
        ProjectSubCategory::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return back();
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/projects/sub-categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/projects/sub-categories/' . $image_name; // save the destination of the file to the database
        }

        $sub_category = ProjectSubCategory::find($request->sub_category_id);
        $sub_category->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function recover(Request $request, $sub_category_id)
    {
        $sub_category = ProjectSubCategory::find($sub_category_id);
        $sub_category->status = ProjectSubCategoryStatus::ACTIVE; // mark data as active
        $sub_category->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function delete(Request $request, $sub_category_id)
    {
        $sub_category = ProjectSubCategory::find($sub_category_id);
        $sub_category->status = ProjectSubCategoryStatus::INACTIVE; // mark data as inactive
        $sub_category->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
