<?php

namespace App\Http\Controllers\Front\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\Package;

class PackageController extends Controller
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

        $package = Package::find($request->package_id);
        $name = $request->name;
        $email = $request->email;
        $subject = $name . ' wants to inquire for ' . $package->name;
        $description = $request->description;

        Mail::send('emails.packages.inquire', [
            'name' => $name,
            'email' => $email,
            'description' => $description,
            'package' => $package,
        ], function ($message) use ($email, $package, $name, $subject) {
            $message->to('ronalyn.sales@bigfourglobal.com', 'TNCPC Warehouse')
                ->from($email)
                ->subject($subject);
        });

        return back();
    }
}
