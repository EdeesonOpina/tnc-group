<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\ItemStatus;
use App\Models\SupplyStatus;
use App\Models\SupplierStatus;

class SupplyController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->name ?? '*';

        return redirect()->route('admin.supplies.filter', [$request->supplier_id, $name])->withInput();
    }

    public function filter($supplier_id, $name)
    {
        $supplier = Supplier::find($supplier_id);

        $query = Item::leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->select('items.*', 'categories.name as category_name', 'brands.name as brand_name')
                    ->where('items.status', ItemStatus::ACTIVE)
                    ->orderBy('items.created_at', 'desc');

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        $items = $query->paginate(15);

        $supplier_items = Supply::where('supplier_id', $supplier_id)
                        ->where('status', SupplyStatus::ACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->take(5);

        return view('admin.suppliers.manage', compact(
            'supplier_items',
            'supplier',
            'items'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();
        $data['supplier_id'] = $request->supplier_id;
        $data['item_id'] = $request->item_id;
        $data['price'] = $request->price;
        $data['status'] = SupplyStatus::ACTIVE; // if you want to insert to a specific column
        Supply::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function price(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();
        $supply = Supply::find($request->supply_id);
        $supply->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function recover(Request $request, $supplier_id, $supply_id)
    {
        $supply = Supply::find($supply_id);
        $supply->status = SupplyStatus::ACTIVE; // mark data as inactive
        $supply->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $supplier_id, $supply_id)
    {
        $supply = Supply::find($supply_id);
        $supply->status = SupplyStatus::INACTIVE; // mark data as inactive
        $supply->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }

    public function masterlist(Request $request, $supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $supplies = Supply::where('supplier_id', $supplier_id)
                        ->where('status', SupplyStatus::ACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.suppliers.items.masterlist', compact(
            'supplier',
            'supplies'
        ));
    }
}
