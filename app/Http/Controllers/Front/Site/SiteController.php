<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return redirect()->route('login');
    }
}
