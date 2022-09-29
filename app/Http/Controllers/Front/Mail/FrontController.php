<?php

namespace App\Http\Controllers\Front\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\EmailNewsletter;
use App\Models\EmailNewsletterStatus;

class FrontController extends Controller
{
    public function contact(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $name = $request->name;
        $email = $request->email;
        $subject = 'Contact - A visitor sent you a message';
        $description = $request->description;

        Mail::send('front.emails.contact', [
            'name' => $name,
            'email' => $email,
            'description' => $description,
        ], function ($message) use ($email, $subject) {
            $message->to('ronalyn.sales@bigfourglobal.com', 'TNCPC Warehouse')
                ->from($email)
                ->subject($subject);
        });

        return view('front.success.contact');
    }

    public function newsletter(Request $request)
    {
        $rules = [
            'email' => 'required|unique:email_newsletters',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $data['status'] = EmailNewsletterStatus::ACTIVE;
        EmailNewsletter::create($data);

        $email = $request->email;
        $internals_subject = 'Subscription to our newsletter';
        $visitor_subject = 'You are now subscribed to our newsletter';

        Mail::send('front.emails.internals.newsletter', [
            'email' => $email,
        ], function ($message) use ($email, $subject) {
            $message->to('ronalyn.sales@bigfourglobal.com', 'TNCPC Warehouse')
                ->from($email)
                ->subject($internals_subject);
        });

        Mail::send('front.emails.newsletter', [
            'email' => $email,
        ], function ($message) use ($email, $subject) {
            $message->to($email, 'TNCPC Warehouse')
                ->from($email)
                ->subject($visitor_subject);
        });

        return view('front.success.newsletter');
    }
}
