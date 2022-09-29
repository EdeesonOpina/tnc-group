<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Supply;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\OrderStatus;
use App\Models\SupplyStatus;
use App\Models\GoodsReceipt;
use App\Models\BranchStatus;
use App\Models\PurchaseOrder;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\ItemSerialNumberStatus;

class InventoryBranchController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.inventories.branches.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Branch::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $branches = $query->paginate(15);

        return view('admin.inventories.show', compact(
            'branches'
        ));
    }
}
