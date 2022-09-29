<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\BranchStatus;
use App\Models\InventoryStatus;

class InventoryController extends Controller
{
    public function print($so_number)
    {
        $branch = Branch::find(auth()->user()->branch->id);

        $inventories = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                            ->select('inventories.*')
                            ->where('inventories.branch_id', auth()->user()->branch->id)
                            ->where('inventories.qty', '>', 0)
                            ->where('inventories.status', InventoryStatus::ACTIVE)
                            ->orderBy('items.name', 'asc')
                            ->get();

        return view('admin.inventories.print', compact(
            'branch',
            'inventories'
        ));
    }
}
