<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\Models\User;
use App\Models\UserStatus;

class SocialiteController extends Controller
{
    public function redirect($service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function callback(Request $request, $service)
    {
        /* fetch the user data returned by the api */
        $user = Socialite::with($service)->stateless()->user(); // stateless was used because of the new format
        // dd($user);

        if (User::where('email', $user->email)
            ->exists() || User::where('email', $user->id)
            ->exists()) {

            /* fetch the existing user */
            if (User::where('email', $user->email)->exists()) {
                $db_user = User::where('email', $user->email)
                                ->first();
            } else {
                $db_user = User::where('email', $user->id)
                                    ->first();
            }

            Auth::loginUsingId($db_user->id);
            return redirect()->route('shop');
        }
        
        if ($service == 'facebook') {
            /* Restricts it to only 2 values, for names like Billy Bob Jones */
            $splitName = explode(' ', $user->name, 2);

            /* get the firstname and lastname */
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : ''; // if last name doesn't exist, make it empty

            $data['firstname'] = $first_name;
            $data['lastname'] = $last_name;
            $data['email'] = $user->email ?? $user->id; // because on facebook users can create account with or w/o emails
            $data['mobile'] = '-';
            $data['password'] = bcrypt(rand());
            $data['provider'] = 'facebook';
            $data['oauth_id'] = $user->id;
            $data['avatar'] =  $user->avatar_original;
            $data['role'] = 'Customer';
            $data['status'] = UserStatus::ACTIVE;
            User::create($data);

            /* fetch the newly created data via socialite */
            $oauth_user = User::where('oauth_id', $user->id)
                            ->latest()
                            ->first();

            Auth::loginUsingId($oauth_user->id);
            return redirect()->route('shop');
        }

        if ($service == 'google') {
            /* Restricts it to only 2 values, for names like Billy Bob Jones */
            $splitName = explode(' ', $user->name, 2);

            /* get the firstname and lastname */
            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : ''; // if last name doesn't exist, make it empty
            
            $data['firstname'] = $first_name;
            $data['lastname'] = $last_name;
            $data['email'] = $user->email;
            $data['mobile'] = '-';
            $data['password'] = bcrypt(rand());
            $data['provider'] = 'google';
            $data['oauth_id'] = $user->id;
            $data['avatar'] =  $user->avatar_original;
            $data['role'] = 'Customer';
            $data['status'] = UserStatus::ACTIVE;
            User::create($data);

            /* fetch the newly created data via socialite */
            $oauth_user = User::where('oauth_id', $user->id)
                            ->latest()
                            ->first();

            Auth::loginUsingId($oauth_user->id);
            return redirect()->route('shop');
        }
    }
}
