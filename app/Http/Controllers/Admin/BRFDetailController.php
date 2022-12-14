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
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class BRFDetailController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'description' => 'nullable',
            'file' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('file')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('file')->getClientOriginalExtension(); // set unique name for that file
            $request->file('file')->move('uploads/files/brf', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/brf/' . $image_name; // save the destination of the file to the database
        }

        $data['price'] = str_replace(',', '', $request->price);
        $data['total'] = $request->qty * str_replace(',', '', $request->price);
        $data['status'] = BudgetRequestFormDetailStatus::FOR_APPROVAL; // if you want to insert to a specific column
        $budget_request_form_detail = BudgetRequestFormDetail::create($data); // create data in a model

        $brf = BudgetRequestForm::find($budget_request_form_detail->budget_request_form_id);
        $total = BudgetRequestFormDetail::where('budget_request_form_id', $brf->id)
                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                        ->sum('total') - $data['total'];
        $brf->total = $total;
        $brf->save();

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.brf.details.approve', [$budget_request_form_detail->id]);
    }

    public function view($budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);

        return view('admin.brf.view', compact(
            'budget_request_form'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
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
            $request->file('file')->move('uploads/files/brf', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/brf/' . $image_name; // save the destination of the file to the database
        }

        $budget_request_form_detail = BudgetRequestFormDetail::find($request->budget_request_form_detail_id);
        $data['price'] = str_replace(',', '', $request->price);
        $data['total'] = $request->qty * str_replace(',', '', $request->price);
        $budget_request_form_detail->fill($data)->save();

        $budget_request_form = BudgetRequestForm::find($budget_request_form_detail->budget_request_form_id);
        $budget_request_form_details_total = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->sum('total');
        $budget_request_form->total = $budget_request_form_details_total;
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function approve(Request $request, $budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);
        $budget_request_form_detail->status = BudgetRequestFormStatus::APPROVED; // mark data as cancelled
        $budget_request_form_detail->save();

        $budget_request_form = BudgetRequestForm::find($budget_request_form_detail->budget_request_form_id);
        $budget_request_form->total += $budget_request_form_detail->total;
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been approved');
        return redirect()->route('internals.brf.manage', [$budget_request_form->id]);
    }

    public function disapprove(Request $request, $budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);
        $budget_request_form_detail->status = BudgetRequestFormStatus::DISAPPROVED; // mark data as cancelled
        $budget_request_form_detail->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function recover(Request $request, $budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);
        $budget_request_form_detail->status = BudgetRequestFormStatus::FOR_APPROVAL; // mark data as active
        $budget_request_form_detail->save();

        $budget_request_form = BudgetRequestForm::find($budget_request_form_detail->budget_request_form_id);
        $budget_request_form->total += $budget_request_form_detail->total;
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function cancel(Request $request, $budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);
        $budget_request_form_detail->status = BudgetRequestFormStatus::CANCELLED; // mark data as cancelled
        $budget_request_form_detail->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function delete(Request $request, $budget_request_form_detail_id)
    {
        $budget_request_form_detail = BudgetRequestFormDetail::find($budget_request_form_detail_id);
        $budget_request_form_detail->status = BudgetRequestFormStatus::INACTIVE; // mark data as inactive
        $budget_request_form_detail->save();

        $budget_request_form = BudgetRequestForm::find($budget_request_form_detail->budget_request_form_id);
        $budget_request_form->total -= $budget_request_form_detail->total;
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
