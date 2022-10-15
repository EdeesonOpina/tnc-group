<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Country;

class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile.show');
    }

    public function edit()
    {
        return view('auth.profile.edit');
    }

    public function view($user_id)
    {
        $user = User::find($user_id);
        $country = Country::find($user->country_id);
        // $user_links = UserLink::where('user_id', $user->id)->get();

        return view('auth.profile.view', compact(
            'user',
            'country'
        ));
    }

    public function change_password(Request $request)
    {
        $rules = [
            'password' => 'required|same:confirm_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = User::find(auth()->user()->id);
        $data['password'] = bcrypt($request->password);
        $user->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }
}
