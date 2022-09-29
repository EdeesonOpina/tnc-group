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
use App\Models\SubCategoryStatus;

class ItemPhotoController extends Controller
{
    public function show($item_id)
    {
        $item = Item::find($item_id);
        $item_photos = ItemPhoto::where('item_id', $item_id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('admin.items.photos.show', compact(
            'item',
            'item_photos'
        ));
    }

    public function search(Request $request)
    {
        $barcode = $request->barcode ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.items.photos.filter', [$request->item_id, $barcode, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($item_id, $name, $status, $from_date, $to_date)
    {
        $query = ItemPhoto::where('item_id', $item_id)
                    ->orderBy('created_at', 'desc');

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

        $item_photos = $query->paginate(15);
        $item = Item::find($item_id);

        return view('admin.items.photos.show', compact(
            'item_photos',
            'item'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'image' => 'image|required'
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

        $data['status'] = ItemPhotoStatus::ACTIVE; // if you want to insert to a specific column
        ItemPhoto::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'image' => 'image|required'
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

        $item_photo = ItemPhoto::find($request->item_photo_id);
        $item_photo->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function recover(Request $request, $item_id, $item_photo_id)
    {
        $item_photo = ItemPhoto::find($item_photo_id);
        $item_photo->status = ItemPhotoStatus::ACTIVE; // mark data as active
        $item_photo->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $item_id, $item_photo_id)
    {
        $item_photo = ItemPhoto::find($item_photo_id);
        $item_photo->status = ItemPhotoStatus::INACTIVE; // mark data as inactive
        $item_photo->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
