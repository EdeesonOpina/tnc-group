<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\UserStatus;

class MailController extends Controller
{
    public function verify(Request $request, $email, $user_id)
    {
        $user = User::find($user_id);

        // if the user id does not match the provided email
        if ($email != $user->email) {
            $request->session()->flash('error', 'There is something wrong here.');
            return redirect()->route('login');
        }

        Auth::loginUsingId($user->id);
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->status = UserStatus::ACTIVE;
        $user->save();

        // return redirect()->route('shop');
        return view('front.success.verify');
    }

    public function reset(Request $request, $email, $user_id)
    {
        $user = User::find($user_id);

        // if the user id does not match the provided email
        if ($email != $user->email) {
            $request->session()->flash('error', 'There is something wrong here.');
            return redirect()->route('login');
        }

        return view('auth.guest.reset-password', compact(
            'user'
        ));
    }
}
