<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemPhoto;
use App\Models\ItemStatus;
use App\Models\ItemPhotoStatus;

class ItemController extends Controller
{
    public function view($item_id)
    {
        $item = Item::find($item_id);
        $item_photos = ItemPhoto::where('item_id', $item_id)
                            ->where('status', ItemPhotoStatus::ACTIVE)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('qr.items.view', compact(
            'item_photos',
            'item'
        ));
    }
}
