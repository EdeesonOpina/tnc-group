<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\BranchStatus;
use App\Models\SupplierStatus;
use App\Models\ReturnInventory;
use App\Models\InventoryStatus;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryStatus;
use App\Models\ReturnInventoryItemStatus;

class ReturnInventoryController extends Controller
{
    public function show()
    {
        $return_inventories = ReturnInventory::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.return_inventories.show', compact(
            'return_inventories'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.return-inventories.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = ReturnInventory::leftJoin('suppliers', 'return_inventories.supplier_id', '=', 'suppliers.id')
                        ->select('return_inventories.*')
                        ->orderBy('return_inventories.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('return_inventories.reference_number', $reference_number);
        }

        if ($name != '*') {
            $query->where('suppliers.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('return_inventories.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('return_inventories.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $return_inventories = $query->paginate(15);

        return view('admin.return_inventories.show', compact(
            'return_inventories'
        ));
    }

    public function add()
    {
        $branches = Branch::where('status', BranchStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        $suppliers = Supplier::where('status', SupplierStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        return view('admin.return_inventories.add', compact(
            'branches',
            'suppliers'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'supplier_id' => 'required',
            'branch_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $return_count = str_replace('RT-', '', ReturnInventory::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest return sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'RT-' . str_pad($return_count, 8, '0', STR_PAD_LEFT);
        $data['supplier_id'] = $request->supplier_id;
        $data['branch_id'] = $request->branch_id;
        $data['created_by_user_id'] = auth()->user()->id;
        $data['status'] = ReturnInventoryStatus::ON_PROCESS; // if you want to insert to a specific column
        ReturnInventory::create($data); // create data in a model

        $return_inventory = ReturnInventory::where('created_by_user_id', auth()->user()->id)
                                    ->latest()
                                    ->first();

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('internals.return-inventories.manage', [$return_inventory->id]);
    }

    public function view($return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', ReturnInventoryItemStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.return_inventories.view', compact(
            'return_inventory',
            'return_inventory_items'
        ));
    }

    public function manage($return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);

        $inventories = Inventory::where('qty', '>', 0)
                    ->where('status', InventoryStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', ReturnInventoryItemStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.return_inventories.manage', compact(
            'return_inventory_items',
            'return_inventory',
            'inventories'
        ));
    }

    public function edit($return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);

        return view('admin.return_inventories.edit', compact(
            'return_inventory'
        ));
    }

    public function apply(Request $request)
    {
        
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $return_inventory = ReturnInventory::find($request->return_inventory_id);
        $return_inventory->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.return-inventories');
    }   

    public function recover(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->status = ReturnInventoryStatus::ON_PROCESS; // mark data as on process again upon recovering
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function approve(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->approved_by_user_id = auth()->user()->id;
        $return_inventory->status = ReturnInventoryStatus::APPROVED; // mark data as approved
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->status = ReturnInventoryStatus::DISAPPROVED; // mark data as disapproved
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function cancel(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->status = ReturnInventoryStatus::CANCELLED; // mark data as cancelled
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function finalize(Request $request)
    {
        $data = [];

        $return_inventory = ReturnInventory::find($request->return_inventory_id);
        $data['status'] = ReturnInventoryStatus::FOR_APPROVAL; // mark data as for approval
        $return_inventory->fill($data)->save();

        $request->session()->flash('success', 'Data has been finalized');

        return redirect()->route('internals.return-inventories');
    }
}
