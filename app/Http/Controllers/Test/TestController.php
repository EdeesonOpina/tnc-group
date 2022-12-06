<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Models\Project;
use App\Models\BudgetRequestForm;

class TestController extends Controller
{
    public function version()
    {
        return view('welcome');
    }

    public function email()
    {
        $budget_request_form = BudgetRequestForm::find(5);

        /* dan mar user */
        $name = 'Dan Mar Dumawin';
        $email = 'edeesonopinav4@gmail.com';
        $subject = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' sent a BRF for finance checking';

        /* send mail to user */
        Mail::send('emails.brf.send-to-finance', [
            'brf' => $budget_request_form
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
            ->subject($subject);
        });
    }
}
