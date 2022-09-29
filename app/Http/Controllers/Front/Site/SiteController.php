<?php

namespace App\Http\Controllers\Front\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Inventory;
use App\Models\BrandStatus;
use App\Models\InventoryStatus;

class SiteController extends Controller
{
    public function index()
    {
        // $slider_brands = Brand::where('id', '!=', 1)
        //                     ->where('image', '!=', null)
        //                     ->where('status', BrandStatus::ACTIVE)
        //                     ->get()
        //                     ->take(11);

        // $inventories = Inventory::where('status', InventoryStatus::ACTIVE)
        //                     ->where('price', '>', 0)
        //                     ->orderByRaw('rand()')
        //                     ->get()
        //                     ->take(8);

        // $most_viewed_inventories = Inventory::where('status', InventoryStatus::ACTIVE)
        //                     ->where('inventories.price', '>', 0)
        //                     ->orderBy('views', 'asc')
        //                     ->get()
        //                     ->take(8);

        // return view('front.index', compact(
        //     'most_viewed_inventories',
        //     'slider_brands',
        //     'inventories'
        // ));

        return redirect()->route('login');
    }

    public function help()
    {
        return view('front.help');
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function about()
    {
        return view('front.about');
    }

    public function terms()
    {
        return view('front.terms');
    }

    public function privacy()
    {
        return view('front.privacy');
    }
}
