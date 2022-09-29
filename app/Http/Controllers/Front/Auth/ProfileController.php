<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\UserLink;

class ProfileController extends Controller
{
    public function show()
    {
        return view('front.auth.profile.show');
    }

    public function edit()
    {
        return view('front.auth.profile.edit');
    }
}
