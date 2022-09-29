<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ItemPhoto;
use App\Models\ItemStatus;
use App\Models\BrandStatus;
use App\Models\SubCategory;
use App\Models\CategoryStatus;
use App\Models\ItemPhotoStatus;
use App\Models\ItemSerialNumber;
use App\Models\SubCategoryStatus;
use App\Models\ItemSerialNumberStatus;

class ItemController extends Controller
{
    public function show()
    {
        $items = Item::leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->select('items.*')
                    ->orderBy('items.created_at', 'desc')
                    ->paginate(15);

        return view('admin.items.show', compact(
            'items'
        ));
    }

    public function search(Request $request)
    {
        $barcode = $request->barcode ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.items.filter', [$barcode, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($barcode, $name, $status, $from_date, $to_date)
    {
        $query = Item::leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->select('items.*')
                    ->orderBy('items.created_at', 'desc');

        if ($barcode != '*') {
            $query->where('items.barcode', $barcode);
        }

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('items.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('items.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $items = $query->paginate(15);

        return view('admin.items.show', compact(
            'items'
        ));
    }

    public function add()
    {
        $categories = Category::orderBy('name', 'asc')
                        ->where('status', CategoryStatus::ACTIVE)
                        ->get();

        $sub_categories = SubCategory::orderBy('name', 'asc')
                        ->where('status', SubCategoryStatus::ACTIVE)
                        ->get();

        $brands = Brand::orderBy('name', 'asc')
                        ->where('status', BrandStatus::ACTIVE)
                        ->get();

        return view('admin.items.add', compact(
            'sub_categories',
            'categories',
            'brands'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'image' => 'image|nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/items', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/items/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ItemStatus::ACTIVE; // if you want to insert to a specific column
        Item::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.items');
    }

    public function view($item_id)
    {
        $item = Item::find($item_id);
        $item_photos = ItemPhoto::where('item_id', $item_id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        $item_serial_numbers = ItemSerialNumber::orderBy('created_at', 'desc')
                                            ->paginate(15);

        return view('admin.items.view', compact(
            'item_serial_numbers',
            'item_photos',
            'item'
        ));
    }

    public function manage($item_id)
    {
        $item = Item::find($item_id);

        return redirect()->route('items', [$item->id]);
    }

    public function edit($item_id)
    {
        $item = Item::find($item_id);
        $curr_brand = Brand::find($item->brand_id);
        $curr_category = Category::find($item->category->id);
        $curr_sub_category = SubCategory::find($item->sub_category->id);

        $categories = Category::orderBy('name', 'asc')
                        ->where('status', CategoryStatus::ACTIVE)
                        ->get();

        $sub_categories = SubCategory::orderBy('name', 'asc')
                        ->where('category_id', $curr_category->id)
                        ->where('status', SubCategoryStatus::ACTIVE)
                        ->get();

        $brands = Brand::orderBy('name', 'asc')
                        ->where('status', BrandStatus::ACTIVE)
                        ->get();

        return view('admin.items.edit', compact(
            'item',
            'curr_brand',
            'curr_category',
            'curr_sub_category',
            'sub_categories',
            'categories',
            'brands'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'image' => 'image|nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/items', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/items/' . $image_name; // save the destination of the file to the database
        }

        $item = Item::find($request->item_id);
        $item->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.items');
    }

    public function recover(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        $item->status = ItemStatus::ACTIVE; // mark data as active
        $item->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        $item->status = ItemStatus::INACTIVE; // mark data as inactive
        $item->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
