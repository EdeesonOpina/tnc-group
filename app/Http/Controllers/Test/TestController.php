<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Models\Project;

class TestController extends Controller
{
    public function version()
    {
        return view('welcome');
    }

    public function email()
    {
        // email variables for use function
        $project = Project::find(1);
        $name = auth()->user()->name;
        $email = auth()->user()->email;
        $subject = 'Created a project';

        // send mail to user
        Mail::send('emails.projects.create', [
            'project' => $project
        ], function ($message) use ($name, $email, $subject) {
            $message->to($email, $name)
            ->from(env('APP_EMAIL'))
            ->subject($subject);
        });
    }
}
