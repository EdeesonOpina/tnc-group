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

class OrderController extends Controller
{
    public function create(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'create';
        ActivityLogOrder::create($data); // create data in a model
    }

    public function freeQty(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'update-free-qty';
        ActivityLogOrder::create($data); // create data in a model
    }

    public function qty(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'update-qty';
        ActivityLogOrder::create($data); // create data in a model
    }

    public function price(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'update-price';
        ActivityLogOrder::create($data); // create data in a model
    }

    public function discount(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'update-discount';
        ActivityLogOrder::create($data); // create data in a model
    }

    public function delete(Request $request, $order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['order_id'] = $order_id;
        $data['description'] = 'delete';
        ActivityLogOrder::create($data); // create data in a model
    }
}
