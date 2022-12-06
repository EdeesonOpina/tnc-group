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
use App\Models\Liquidation;
use App\Models\LiquidationStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Supplier;
use App\Models\SupplierStatus;

class BRFController extends Controller
{
    public function show()
    {
        $budget_request_forms = BudgetRequestForm::orderBy('created_at', 'desc')
                    ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.brf.show', compact(
            'budget_request_forms'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.brf.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = BudgetRequestForm::where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('reference_number', $reference_number);
        }

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

        $budget_request_forms = $query->paginate(15);

        return view('admin.brf.show', compact(
            'budget_request_forms'
        ));
    }

    public function add_user()
    {
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.brf.users.add', compact(
            'users'
        ));
    }

    public function edit_user($reference_number)
    {
        $budget_request_form = BudgetRequestForm::where('reference_number', $reference_number)->first();
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.brf.users.edit', compact(
            'budget_request_form',
            'users'
        ));
    }

    public function edit_supplier($reference_number)
    {
        $budget_request_form = BudgetRequestForm::where('reference_number', $reference_number)->first();
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $suppliers = Supplier::where('status', '!=', SupplierStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.brf.suppliers.edit', compact(
            'budget_request_form',
            'suppliers',
            'users'
        ));
    }

    public function add_supplier()
    {
        $suppliers = Supplier::where('status', '!=', SupplierStatus::INACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.brf.suppliers.add', compact(
            'users',
            'suppliers'
        ));
    }

    public function manage($budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form_details = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form_id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->get();
        $budget_request_form_details_total = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form_id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->sum('total');

        return view('admin.brf.manage', compact(
            'budget_request_form',
            'budget_request_form_details_total',
            'budget_request_form_details'
        ));
    }

    public function create_user(Request $request)
    {
        $rules = [
            'reference_number' => 'required|exists:projects',
            'needed_date' => 'required',
            'remarks' => 'nullable',
            'payment_for_user_id' => 'required',
            'name' => 'required',
            'requested_by_user_id' => 'required',
            'checked_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $project = Project::where('reference_number', $request->reference_number)
                        ->where('status', '!=', ProjectStatus::INACTIVE)
                        ->first();

        if ($project->status != ProjectStatus::APPROVED) {
            $request->session()->flash('error', 'Selected CE is still not approved');
            return back();
        }

        $brf_count = str_replace('BRF-', '', BudgetRequestForm::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest brf sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'BRF-' . str_pad($brf_count, 8, '0', STR_PAD_LEFT);
        $data['project_id'] = $project->id;

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $data['total'] = 0;
        $data['status'] = BudgetRequestFormStatus::FOR_APPROVAL; // if you want to insert to a specific column
        $brf = BudgetRequestForm::create($data); // create data in a model

        /* requested by user */
        $name = $brf->requested_by_user->firstname . ' ' . $brf->requested_by_user->lastname;
        $email = $brf->requested_by_user->email;
        $subject = $brf->requested_by_user->firstname . ' ' . $brf->requested_by_user->lastname . ' created a BRF';

        /* send mail to user */
        Mail::send('emails.brf.create', [
            'brf' => $brf
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.brf.manage', [$brf->id]);
    }

    public function create_supplier(Request $request)
    {
        $rules = [
            'reference_number' => 'required|exists:projects',
            'needed_date' => 'required',
            'remarks' => 'nullable',
            'payment_for_supplier_id' => 'required',
            'name' => 'required',
            'requested_by_user_id' => 'required',
            'checked_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $project = Project::where('reference_number', $request->reference_number)
                        ->where('status', '!=', ProjectStatus::INACTIVE)
                        ->first();

        if ($project->status != ProjectStatus::APPROVED) {
            $request->session()->flash('error', 'Selected CE is still not approved');
            return back();
        }

        $brf_count = str_replace('BRF-', '', BudgetRequestForm::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest brf sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'BRF-' . str_pad($brf_count, 8, '0', STR_PAD_LEFT);
        $data['project_id'] = $project->id;

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $data['total'] = 0;
        $data['status'] = BudgetRequestFormStatus::FOR_APPROVAL; // if you want to insert to a specific column
        $brf = BudgetRequestForm::create($data); // create data in a model

        /* requested by user */
        $name = $brf->requested_by_user->firstname . ' ' . $brf->requested_by_user->lastname;
        $email = $brf->requested_by_user->email;
        $subject = $brf->requested_by_user->firstname . ' ' . $brf->requested_by_user->lastname . ' created a BRF';

        /* send mail to user */
        Mail::send('emails.brf.create', [
            'brf' => $brf
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.brf.manage', [$brf->id]);
    }

    public function view($reference_number)
    {
        $budget_request_form = BudgetRequestForm::where('reference_number', $reference_number)->first();
        $budget_request_form_details = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->get();
        $budget_request_form_details_total = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->sum('total');

        $liquidations = Liquidation::where('budget_request_form_id', $budget_request_form->id)
                                ->where('status', '!=', LiquidationStatus::INACTIVE)
                                ->get();
        $liquidations_total = Liquidation::where('budget_request_form_id', $budget_request_form->id)
                                    ->where('status', LiquidationStatus::APPROVED)
                                    ->where('status', '!=', LiquidationStatus::INACTIVE)
                                    ->sum('cost');                                             

        return view('admin.brf.view', compact(
            'liquidations',
            'liquidations_total',
            'budget_request_form',
            'budget_request_form_details_total',
            'budget_request_form_details'
        ));
    }

    public function edit($budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.brf.edit', compact(
            'clients',
            'companies',
            'budget_request_form'
        ));
    }

    public function update_user(Request $request)
    {
        $rules = [
            'needed_date' => 'required',
            'remarks' => 'nullable',
            'payment_for_user_id' => 'required',
            'name' => 'required',
            'requested_by_user_id' => 'required',
            'checked_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $budget_request_form = BudgetRequestForm::find($request->budget_request_form_id);
        $budget_request_form->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.brf.manage', [$budget_request_form->id]);
    }

    public function update_supplier(Request $request)
    {
        $rules = [
            'needed_date' => 'required',
            'remarks' => 'nullable',
            'payment_for_supplier_id' => 'required',
            'name' => 'required',
            'requested_by_user_id' => 'required',
            'checked_by_user_id' => 'required',
            'noted_by_user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $budget_request_form = BudgetRequestForm::find($request->budget_request_form_id);
        $budget_request_form->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('internals.brf.manage', [$budget_request_form->id]);
    }

    public function for_approval(Request $request, $brf_id)
    {
        $brf = BudgetRequestForm::find($brf_id);
        $brf->status = BudgetRequestFormStatus::FOR_APPROVAL; // mark data as for approval
        $brf->save();

        /* checked by user */
        $name = $brf->checked_by_user->firstname . ' ' . $brf->checked_by_user->lastname;
        $email = $brf->checked_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' sent a BRF for approval';

        /* send mail to user */
        Mail::send('emails.brf.create-for-approver', [
            'brf' => $brf
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been sent for approval');
        return back();
    }

    public function for_final_approval(Request $request, $budget_request_form_id)
    {
        $brf = BudgetRequestForm::find($budget_request_form_id);
        $brf->status = BudgetRequestFormStatus::FOR_FINAL_APPROVAL; // mark data as for final approval
        $brf->save();

        /* noted by user */
        $name = $brf->noted_by_user->firstname . ' ' . $brf->noted_by_user->lastname;
        $email = $brf->noted_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' sent a BRF for final approval';

        /* send mail to user */
        Mail::send('emails.brf.for-final-approval', [
            'brf' => $brf
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function approve(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::APPROVED; // mark data as cancelled
        $budget_request_form->save();

        /* requested by user */
        $name = $budget_request_form->requested_by_user->firstname . ' ' . $budget_request_form->requested_by_user->lastname;
        $email = $budget_request_form->requested_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' approved your BRF';

        /* send mail to user */
        Mail::send('emails.brf.approve', [
            'brf' => $budget_request_form
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function send_to_finance(Request $request, $reference_number)
    {
        $budget_request_form = BudgetRequestForm::where('reference_number', $reference_number)->first();
        return $budget_request_form;

        /* dan mar user */
        $name = 'Dan Mar Dumawin';
        $email = 'mdumawin@tnc.com.ph';
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' sent a BRF for finance checking';

        /* send mail to user */
        Mail::send('emails.brf.send-to-finance', [
            'brf' => $budget_request_form
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been sent to finance');
        return back();
    }

    public function disapprove(Request $request)
    {
        $budget_request_form = BudgetRequestForm::find($request->budget_request_form_id);
        $budget_request_form->remarks = $request->remarks;
        $budget_request_form->status = BudgetRequestFormStatus::DISAPPROVED; // mark data as cancelled
        $budget_request_form->save();

        /* requested by user */
        $name = $budget_request_form->requested_by_user->firstname . ' ' . $budget_request_form->requested_by_user->lastname;
        $email = $budget_request_form->requested_by_user->email;
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' disapproved your BRF';

        /* send mail to user */
        Mail::send('emails.brf.disapprove', [
            'brf' => $budget_request_form
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Data has been disapproved');
        return redirect()->route('internals.brf.view', [$budget_request_form->reference_number]);
    }

    public function recover(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::FOR_APPROVAL; // mark data as active
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function cancel(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::CANCELLED; // mark data as cancelled
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function delete(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::INACTIVE; // mark data as inactive
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
