<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\ActivityLogAuth;
use App\Models\ActivityLogOrder;
use App\Models\ActivityLogGoodsReceipt;
use App\Models\ActivityLogPurchaseOrder;

class AuthController extends Controller
{
    public function log(Request $request, $description)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['description'] = $description;
        ActivityLogAuth::create($data); // create data in a model
    }

    public function fail(Request $request, $description)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = 0; // it has failed to do something because user is not logged in
        $data['description'] = $description;
        ActivityLogAuth::create($data); // create data in a model
    }

    public function create(Request $request, $user, $description)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = $user->id; // fetch the data passed here
        $data['description'] = $description;
        ActivityLogAuth::create($data); // create data in a model
    }
}
