<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CategoryStatus;
use App\Models\SubCategoryStatus;

class SubCategoryController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'is_package' => 'required',
            'sort_order' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/sub-categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/sub-categories/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = SubCategoryStatus::ACTIVE; // if you want to insert to a specific column
        SubCategory::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'is_package' => 'required',
            'sort_order' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/sub-categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/sub-categories/' . $image_name; // save the destination of the file to the database
        }

        $sub_category = SubCategory::find($request->sub_category_id);
        $sub_category->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function recover(Request $request, $sub_category_id)
    {
        $sub_category = SubCategory::find($sub_category_id);
        $sub_category->status = SubCategoryStatus::ACTIVE; // mark data as active
        $sub_category->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $sub_category_id)
    {
        $sub_category = SubCategory::find($sub_category_id);
        $sub_category->status = SubCategoryStatus::INACTIVE; // mark data as inactive
        $sub_category->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
