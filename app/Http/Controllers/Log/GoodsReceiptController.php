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

class GoodsReceiptController extends Controller
{
    public function create(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'create';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function update(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'update';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function delete(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'delete';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function approve(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'approve';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function disapprove(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'disapprove';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function clear(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'clear';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }

    public function cancel(Request $request, $goods_receipt_id)
    {
        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['device'] = $request->header('User-Agent');
        $data['uri'] = $request->path();
        $data['auth_id'] = auth()->user()->id;
        $data['goods_receipt_id'] = $goods_receipt_id;
        $data['description'] = 'cancel';
        ActivityLogGoodsReceipt::create($data); // create data in a model
    }
}
