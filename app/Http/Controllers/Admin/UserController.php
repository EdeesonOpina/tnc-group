<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\Company;
use App\Models\CompanyStatus;
use App\Models\UserStatus;

class UserController extends Controller
{
    public function show()
    {
        $users = User::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.users.show', compact(
            'users'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.users.filter', [$name, $role, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $role, $status, $from_date, $to_date)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(firstname, ' ', lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('firstname', $name);
            // $query->orWhere('lastname', $name);
        }

        if ($role != '*') {
            $query->where('role', $role);
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

        $users = $query->paginate(15);

        return view('admin.users.show', compact(
            'users'
        ));
    }

    public function add()
    {
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.users.add', compact(
            'companies'
        ));
    }

    public function corporate_add()
    {
        return view('admin.users.corporate.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'nullable',
            'biography' => 'nullable',
            'email' => 'required|unique:users',
            'company_id' => 'required',
            'mobile' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('signature')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('signature')->getClientOriginalExtension(); // set unique name for that file
            $request->file('signature')->move('uploads/users/signatures', $image_name); // move the file to the laravel project
            $data['signature'] = 'uploads/users/signatures/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = UserStatus::PENDING; // if you want to insert to a specific column
        $data['password'] = bcrypt('123123123');
        User::create($data); // create data in a model

        // email variables for use function
        $user = User::where('email', $request->email)->first(); // fetch the user data after creations
        $name = $user->name;
        $email = $user->email;
        $subject = 'One more step! Please activate your account.';

        // send mail to user
        // Mail::send('auth.emails.register', [
        //     'user' => $user
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('APP_EMAIL'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('admin.users');
    }

    public function corporate_create(Request $request)
    {
        $rules = [
            'corporate' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'nullable',
            'biography' => 'nullable',
            'email' => 'required|unique:users',
            'mobile' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['company_id'] = 0; // if you want to insert to a specific column
        $data['status'] = UserStatus::PENDING; // if you want to insert to a specific column
        $data['password'] = bcrypt('123123123');
        User::create($data); // create data in a model

        // email variables for use function
        $user = User::where('email', $request->email)->first(); // fetch the user data after creations
        $name = $user->name;
        $email = $user->email;
        $subject = 'One more step! Please activate your account.';

        // send mail to user
        // Mail::send('auth.emails.register', [
        //     'user' => $user
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('APP_EMAIL'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.users');
    }

    public function view($user_id)
    {
        $user = User::find($user_id);

        return view('admin.users.view', compact(
            'user'
        ));
    }

    public function edit($user_id)
    {
        $user = User::find($user_id);
        $countries = Country::where('status', 1)->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.users.edit', compact(
            'user',
            'companies',
            'countries'
        ));
    }

    public function corporate_edit($user_id)
    {
        $user = User::find($user_id);

        return view('admin.users.corporate.edit', compact(
            'user',
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'nullable',
            'email' => 'required',
            'company_id' => 'required',
            'mobile' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        
        $data = $request->all();

        if ($request->file('signature')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('signature')->getClientOriginalExtension(); // set unique name for that file
            $request->file('signature')->move('uploads/users/signatures', $image_name); // move the file to the laravel project
            $data['signature'] = 'uploads/users/signatures/' . $image_name; // save the destination of the file to the database
        }

        $user = User::find($request->user_id);
        $user->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.users.view', [$user->id]);
    }

    public function corporate_update(Request $request)
    {
        $rules = [
            'corporate' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'nullable',
            'biography' => 'nullable',
            'email' => 'required',
            'mobile' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        
        $data = $request->all();
        $user = User::find($request->user_id);
        $user->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('admin.users.view', [$user->id]);
    }

    public function password(Request $request)
    {
        $rules = [
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        
        $data = $request->all();

        $user = User::find($request->user_id);
        $data['password'] = bcrypt($request->password);
        $user->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.users.view', [$user->id]);
    }

    public function recover(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->status = UserStatus::ACTIVE; // mark data as active
        $user->save();

        $request->session()->flash('success', 'Data has been recovered');

        return redirect()->route('admin.users');
    }

    public function delete(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->status = UserStatus::INACTIVE; // mark data as inactive
        $user->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }

    public function resend(Request $request, $user_id)
    {
        // email variables for use function
        $user = User::find($user_id);
        $name = $user->name;
        $email = $user->email;
        $subject = 'One more step! Please activate your account.';

        // send mail to user
        Mail::send('auth.emails.register', [
            'user' => $user
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('APP_EMAIL'))
            ->subject($subject);
        });

        $request->session()->flash('success', 'Resent email confirmation');

        return back();
    }
}
