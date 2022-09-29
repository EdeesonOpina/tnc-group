<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\UserStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentCreditStatus;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.guest.login');
    }

    public function attempt(Request $request)
    {
        $rules = [
            'email'=>'required',
            'password'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        if (Auth::attempt([
            'email'     =>  $request->input('email'),
            'password'  =>  $request->input('password'),
        ])) {
            /* log the activity */
            app('App\Http\Controllers\Log\AuthController')->log($request, 'logged in');

            if (auth()->user()->role == 'Customer') // if role is customer return back with auth
                return redirect()->route('site.index');

            return redirect()->route('auth.dashboard');
        } else {
            /* log the failed activity */
            app('App\Http\Controllers\Log\AuthController')->fail($request, $request->email . ' login failed due to wrong credentials');

            $request->session()->flash('error', 'Looks like you have entered wrong login credentials');
            return back()->withInput();
        }
    }

    public function register()
    {
        $countries = Country::where('status', 1)
                        ->get();

        return view('auth.guest.register', compact(
            'countries'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'country_id' => 'required',
            'terms_and_conditions' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|same:confirm_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['role'] = 'Customer'; // if you want to insert to a specific column
        $data['status'] = UserStatus::PENDING;
        $data['password'] = bcrypt($request->password); // make sure to encrpyt the password
        User::create($data); // create data in a model

        // email variables for use function
        $user = User::where('email', $request->email)->first(); // fetch the user data after creations
        $name = $user->firstname . ' ' . $user->lastname;
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

        return view('auth.success.register');
    }

    public function forgot()
    {
        return view('auth.guest.forgot-password');
    }

    public function reset(Request $request)
    {
        $rules = [
            'email' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        // email variables for use function
        $user = User::where('email', $request->email)->first(); // fetch the user data after creations
        $name = $user->firstname . ' ' . $user->lastname;
        $email = $user->email;
        $subject = 'Reset Password Request';

        // send mail to user
        Mail::send('auth.emails.reset-password', [
            'user' => $user
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('APP_EMAIL'))
            ->subject($subject);
        });

        return view('auth.success.forgot-password', compact(
            'user'
        ));
    }

    public function change(Request $request)
    {
        $rules = [
            'password' => 'required|same:confirm_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = User::find($request->user_id);
        $data['password'] = bcrypt($request->password);
        $user->fill($data)->save();

        return view('auth.success.reset-password', compact(
            'user'
        ));
    }

    public function logout(Request $request)
    {
        /* log the activity */
        app('App\Http\Controllers\Log\AuthController')->log($request, 'logged out');

        Auth::logout();

        return redirect()->route('site.index');
    }
}
