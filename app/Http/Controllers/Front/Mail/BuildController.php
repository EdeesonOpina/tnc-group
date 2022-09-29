<?php

namespace App\Http\Controllers\Front\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\Item;
use App\Models\BuildItem;
use App\Models\BuildItemStatus;

class BuildController extends Controller
{
    public function inquire(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'nullable',
            'mobile' => 'required',
            'email' => 'required',
            'description' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        if (auth()->check()) { /* if the user is logged */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('user_id', auth()->user()->id)
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('user_id', auth()->user()->id)
                                        ->sum('total');
        } else { /* if the user is just a guest */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('ip_address', $request->getClientIp())
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('ip_address', $request->getClientIp())
                                        ->sum('total');
        }

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $phone = $request->phone;
        $mobile = $request->mobile;
        $email = $request->email;
        $subject = 'PC Build Inquiry';
        $description = $request->description;

        Mail::send('front.emails.inquire-build', [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $phone,
            'mobile' => $mobile,
            'email' => $email,
            'description' => $description,
            'build_items' => $build_items,
            'build_items_total' => $build_items_total,
        ], function ($message) use ($email, $subject) {
            $message->to('ronalyn.sales@bigfourglobal.com', 'TNCPC Warehouse')
                ->from($email)
                ->subject($subject);
        });

        return view('front.success.inquire-build');
    }
}
