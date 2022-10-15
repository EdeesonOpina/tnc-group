<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
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
        $reference_number = $request->reference_number ?? '*';
        $category_id = $request->category_id ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.liquidations.filter', [$reference_number, $category_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $category_id, $status, $from_date, $to_date)
    {
        $query = Liquidation::leftJoin('budget_request_forms', 'liquidations.budget_request_form_id', '=', 'budget_request_forms.id')
                        ->select('liquidations.*')
                        ->orderBy('liquidations.date', 'desc');

        if ($reference_number != '*') {
            $query->where('budget_request_forms.reference_number', $reference_number);
        }

        if ($category_id != '*') {
            $query->where('liquidations.category_id', $category_id);
        }

        if ($status != '*') {
            $query->where('liquidations.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('liquidations.date', [$from_date, $to_date]);
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
            'reference_number' => 'required|exists:budget_request_forms',
            'category_id' => 'required',
            'date' => 'required',
            'cost' => 'required|numeric',
            'description' => 'required',
            'name' => 'required',
            'image' => 'nullable|image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $budget_request_form = BudgetRequestForm::where('reference_number', $request->reference_number)
                                            ->first();

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/liquidations', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/liquidations/' . $image_name; // save the destination of the file to the database
        }

        $data['budget_request_form_id'] = $budget_request_form->id;
        $data['status'] = LiquidationStatus::FOR_APPROVAL; // if you want to insert to a specific column
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
            'cost' => 'required|numeric',
            'name' => 'required',
            'description' => 'required',
            'note' => 'nullable',
            'image' => 'nullable|image',
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

    public function approve(Request $request, $liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);
        $liquidation->status = LiquidationStatus::APPROVED; // mark data as for approved
        $liquidation->save();

        $request->session()->flash('success', 'Data has been approved');
        return back();
    }

    public function disapprove(Request $request, $liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);
        $liquidation->status = LiquidationStatus::DISAPPROVED; // mark data as for disapproved
        $liquidation->save();

        $request->session()->flash('success', 'Data has been disapproved');
        return back();
    }

    public function recover(Request $request, $liquidation_id)
    {
        $liquidation = Liquidation::find($liquidation_id);
        $liquidation->status = LiquidationStatus::FOR_APPROVAL; // mark data as for approval
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
