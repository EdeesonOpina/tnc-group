<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Inventory;
use App\Models\BrandStatus;
use App\Models\InventoryStatus;

class ShopController extends Controller
{
    public function show()
    {
        $inventories = Inventory::where('status', InventoryStatus::ACTIVE)
                            ->where('price', '>', 0)
                            ->orderBy('views', 'asc')
                            ->paginate(12);

        return view('front.shop.show', compact(
            'inventories'
        ));
    }
}
