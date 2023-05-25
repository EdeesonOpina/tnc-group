<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Company;
use App\Models\Inventory;
use App\Models\BranchStatus;
use App\Models\ItemStatus;
use App\Models\InventoryStatus;

class InventoryController extends Controller
{
    public function print($company_id)
    {
        if ($company_id != '*') {
            $company = Company::find($company_id);

            $inventories = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                                ->select('inventories.*')
                                ->where('inventories.company_id', $company->id)
                                ->where('inventories.qty', '>', 0)
                                ->where('inventories.status', InventoryStatus::ACTIVE)
                                ->where('items.status', ItemStatus::ACTIVE)
                                ->orderBy('items.name', 'asc')
                                ->get();
        } else {
            $inventories = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                                ->select('inventories.*')
                                ->where('inventories.qty', '>', 0)
                                ->where('inventories.status', InventoryStatus::ACTIVE)
                                ->where('items.status', ItemStatus::ACTIVE)
                                ->orderBy('items.name', 'asc')
                                ->get();

            $company = null;
        }
        

        return view('admin.inventories.print', compact(
            'company',
            'inventories'
        ));
    }
}
