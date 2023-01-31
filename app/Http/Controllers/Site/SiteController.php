<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return view('site.index');
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function about()
    {
        return view('site.about');
    }

    public function business_units()
    {
        return view('site.business-units');
    }

    public function services($page_id)
    {
        if ($page_id == '*')
            return view('site.services.show');

        if ($page_id == 1)
            return view('site.services.1');

        if ($page_id == 2)
            return view('site.services.2');

        if ($page_id == 3)
            return view('site.services.3');

        if ($page_id == 4)
            return view('site.services.4');

        if ($page_id == 5)
            return view('site.services.5');

        if ($page_id == 6)
            return view('site.services.6');
    }
}
