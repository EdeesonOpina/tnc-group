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

class PurchaseOrderController extends Controller
{
    public function create(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'create';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function update(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'update';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function delete(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'delete';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function recover(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'recover';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function cancel(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'cancel';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function approve(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'approve';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function disapprove(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'disapprove';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function finalize(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'finalize';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }

    public function clear(Request $request, $purchase_order_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['description'] = 'clear';
        ActivityLogPurchaseOrder::create($data); // create data in a model
    }
}
