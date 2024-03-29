<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Category;
use App\Models\CategoryStatus;

class CategoryController extends Controller
{
    public function show()
    {
        $categories = Category::orderBy('created_at', 'desc')
                    ->where('status', '!=', CategoryStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.categories.show', compact(
            'categories'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.categories.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Category::orderBy('created_at', 'desc');

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

        $categories = $query->paginate(15);

        return view('admin.categories.show', compact(
            'categories'
        ));
    }

    public function add()
    {
        return view('admin.categories.add');
    }

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
            $request->file('image')->move('uploads/images/categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/categories/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = CategoryStatus::ACTIVE; // if you want to insert to a specific column
        Category::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.categories');
    }

    public function view($category_id)
    {
        $category = Category::find($category_id);

        return view('admin.categories.view', compact(
            'category'
        ));
    }

    public function edit($category_id)
    {
        $category = Category::find($category_id);

        return view('admin.categories.edit', compact(
            'category'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'is_package' => 'required',
            'sort_order' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);


        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/categories', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/categories/' . $image_name; // save the destination of the file to the database
        }

        $category = Category::find($request->branch_id);
        $category->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.categories');
    }

    public function recover(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        $category->status = CategoryStatus::ACTIVE; // mark data as active
        $category->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        $category->status = CategoryStatus::INACTIVE; // mark data as inactive
        $category->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
