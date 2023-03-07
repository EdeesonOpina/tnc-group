<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Order;
use App\Models\Company;
use App\Models\Supply;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\OrderStatus;
use App\Models\SupplyStatus;
use App\Models\GoodsReceipt;
use App\Models\CompanyStatus;
use App\Models\PurchaseOrder;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\ItemSerialNumberStatus;

class InventoryController extends Controller
{
    public function show()
    {
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.inventories.show', compact(
            'companies'
        ));
    }

    public function view($company_id)
    {
        $company = Company::find($company_id);

        $inventories = Inventory::where('company_id', $company_id)
                            ->where('status', InventoryStatus::ACTIVE)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('admin.inventories.view', compact(
            'company',
            'inventories'
        ));
    }

    public function manage($company_id)
    {
        $company = Company::find($company_id);

        // $inventories = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
        //                     ->select('inventories.*')
        //                     ->where('inventories.company_id', $company_id)
        //                     ->where('inventories.status', InventoryStatus::ACTIVE)
        //                     ->where('items.status', ItemStatus::ACTIVE)
        //                     ->orderBy('inventories.created_at', 'desc')
        //                     ->paginate(15);

        $inventories = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                            ->select('inventories.*')
                            ->where('inventories.company_id', $company_id)
                            ->where('inventories.status', InventoryStatus::ACTIVE)
                            ->where('items.status', ItemStatus::ACTIVE)
                            ->orderBy('inventories.created_at', 'desc')
                            ->paginate(5);

        return view('admin.inventories.manage', compact(
            'company',
            'inventories'
        ));
    }

    public function search(Request $request, $company_id)
    {
        $barcode = $request->barcode ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.inventories.items.filter', [$company_id, $barcode, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($company_id, $barcode, $name, $status, $from_date, $to_date)
    {
        $query = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                    ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('items.*', 'brands.*', 'inventories.*')
                    ->where('company_id', $company_id)
                    ->where('inventories.status', InventoryStatus::ACTIVE)
                    ->where('items.status', ItemStatus::ACTIVE)
                    ->orderBy('inventories.created_at', 'desc');

        if ($barcode != '*') {
            $query->where('items.barcode', $barcode);
        }

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('inventories.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('inventories.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $inventories = $query->paginate(15);

        $company = Company::find($company_id);

        return view('admin.inventories.manage', compact(
            'company',
            'inventories'
        ));
    }

    public function price(Request $request)
    {
        $rules = [
            'price' => 'required',
            'agent_price' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        
        if ($request->price <= 0) {
            $request->session()->flash('error', 'Invalid price');
            return back()->withInput();
        }

        $data = $request->all();

        $inventory = Inventory::find($request->inventory_id);
        $inventory->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back()->withInput();
    }

    public function discount(Request $request)
    {
        $rules = [
            'discount' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $inventory = Inventory::find($request->inventory_id);
        $inventory->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back()->withInput();
    }

    public function barcode(Request $request)
    {
        $rules = [
            'barcode' => 'required|unique:items'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $inventory = Inventory::find($request->inventory_id);
        $item = Item::find($inventory->item->id);
        $item->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back()->withInput();
    }
}
