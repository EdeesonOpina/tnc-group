<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Liquidation;
use App\Models\LiquidationStatus;
use App\Models\LiquidationCategory;
use App\Models\LiquidationCategoryStatus;

class LiquidationController extends Controller
{
    public function show()
    {
        $liquidations = Liquidation::orderBy('created_at', 'desc')
                        ->paginate(15);

        $liquidation_categories = LiquidationCategory::where('status', LiquidationCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.liquidations.show', compact(
            'liquidation_categories',
            'liquidations'
        ));
    }

    public function search(Request $request)
    {
        $description = $request->description ?? '*';
        $category_id = $request->category_id ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.liquidations.filter', [$description, $category_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($description, $category_id, $status, $from_date, $to_date)
    {
        $query = Liquidation::orderBy('date', 'desc');

        if ($description != '*') {
            $query->where('description', 'LIKE', '%' . $description . '%');
        }

        if ($category_id != '*') {
            $query->where('category_id', $category_id);
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('date', [$from_date, $to_date]);
        }
        /* date filter */

        $liquidations = $query->paginate(15);
        $liquidation_categories = LiquidationCategory::where('status', LiquidationCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.liquidations.show', compact(
            'liquidation_categories',
            'liquidations'
        ));
    }

    public function add()
    {
        $liquidation_categories = LiquidationCategory::where('status', LiquidationCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.liquidations.add', compact(
            'liquidation_categories'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'date' => 'required',
            'cost' => 'required|numeric',
            'description' => 'required',
            'note' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/liquidations', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/liquidations/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = LiquidationStatus::ACTIVE; // if you want to insert to a specific column
        Liquidation::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('accounting.liquidations');
    }

    public function view($liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);

        return view('admin.liquidations.view', compact(
            'liquidation'
        ));
    }

    public function edit($liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);

        $liquidation_categories = LiquidationCategory::where('status', LiquidationCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.liquidations.edit', compact(
            'liquidation_categories',
            'liquidation'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'date' => 'required',
            'company_id' => 'nullable',
            'cost' => 'required|numeric',
            'description' => 'required',
            'note' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/liquidations', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/liquidations/' . $image_name; // save the destination of the file to the database
        }

        $liquidation = Liquidation::find($request->liquidation_id);
        $liquidation->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('accounting.liquidations');
    }

    public function recover(Request $request, $liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);
        $liquidation->status = LiquidationStatus::ACTIVE; // mark data as active
        $liquidation->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);
        $liquidation->status = LiquidationStatus::INACTIVE; // mark data as inactive
        $liquidation->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
