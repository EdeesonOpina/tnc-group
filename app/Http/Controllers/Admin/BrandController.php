<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Brand;
use App\Models\BrandStatus;

class BrandController extends Controller
{
    public function show()
    {
        $brands = Brand::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.brands.show', compact(
            'brands'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.brands.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Brand::orderBy('created_at', 'desc');

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

        $brands = $query->paginate(15);

        return view('admin.brands.show', compact(
            'brands'
        ));
    }

    public function add()
    {
        return view('admin.brands.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brands', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brands/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = BrandStatus::ACTIVE; // if you want to insert to a specific column
        Brand::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.brands');
    }

    public function view($brand_id)
    {
        $brand = Brand::find($brand_id);

        return view('admin.brands.view', compact(
            'brand'
        ));
    }

    public function edit($brand_id)
    {
        $brand = Brand::find($brand_id);

        return view('admin.brands.edit', compact(
            'brand'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brands', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brands/' . $image_name; // save the destination of the file to the database
        }

        $brand = Brand::find($request->brand_id);
        $brand->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.brands');
    }

    public function recover(Request $request, $brand_id)
    {
        $brand = Brand::find($brand_id);
        $brand->status = BrandStatus::ACTIVE; // mark data as active
        $brand->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $brand_id)
    {
        $brand = Brand::find($brand_id);
        $brand->status = BrandStatus::INACTIVE; // mark data as inactive
        $brand->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
