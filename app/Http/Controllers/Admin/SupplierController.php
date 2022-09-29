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
use App\Models\Country;
use App\Models\Supplier;
use App\Models\ItemStatus;
use App\Models\SupplyStatus;
use App\Models\SupplierStatus;

class SupplierController extends Controller
{
    public function show()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.suppliers.show', compact(
            'suppliers'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.suppliers.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Supplier::orderBy('created_at', 'desc');

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

        $suppliers = $query->paginate(15);

        return view('admin.suppliers.show', compact(
            'suppliers'
        ));
    }

    public function add()
    {
        return view('admin.suppliers.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/suppliers', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/suppliers/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = SupplierStatus::ACTIVE; // if you want to insert to a specific column
        Supplier::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.suppliers');
    }

    public function view($supplier_id)
    {
        $supplier = Supplier::find($supplier_id);

        return view('admin.suppliers.view', compact(
            'supplier'
        ));
    }

    public function manage($supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $items = Item::leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->select('items.*', 'categories.name as category_name', 'brands.name as brand_name')
                    ->where('items.status', ItemStatus::ACTIVE)
                    ->orderBy('items.created_at', 'desc')
                    ->paginate(15);

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

    public function edit($supplier_id)
    {
        $supplier = Supplier::find($supplier_id);

        return view('admin.suppliers.edit', compact(
            'supplier'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/suppliers', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/suppliers/' . $image_name; // save the destination of the file to the database
        }

        $supplier = Supplier::find($request->supplier_id);
        $supplier->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.suppliers');
    }

    public function recover(Request $request, $supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $supplier->status = SupplierStatus::ACTIVE; // mark data as active
        $supplier->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $supplier->status = SupplierStatus::INACTIVE; // mark data as inactive
        $supplier->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
