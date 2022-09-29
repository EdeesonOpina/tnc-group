<?php

namespace App\Http\Controllers\Front\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\Item;

class ItemController extends Controller
{
    public function inquire(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $item = Item::find($request->package_id);
        $name = $request->name;
        $email = $request->email;
        $subject = $name . ' wants to inquire for ' . $item->name;
        $description = $request->description;

        Mail::send('emails.items.inquire', [
            'name' => $name,
            'email' => $email,
            'description' => $description,
            'item' => $item,
        ], function ($message) use ($email, $item, $name, $subject) {
            $message->to('ronalyn.sales@bigfourglobal.com', 'TNCPC Warehouse')
                ->from($email)
                ->subject($subject);
        });

        return back();
    }
}
