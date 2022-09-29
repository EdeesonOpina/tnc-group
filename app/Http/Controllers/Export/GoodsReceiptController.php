<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\GoodsReceiptExport;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
use Auth;
use Mail;
use Validator;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\GoodsReceipt;
use App\Models\DeliveryReceipt;
use App\Models\DeliveryReceiptStatus;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class GoodsReceiptController extends Controller
{
    public function excel($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);

        return Excel::download(new GoodsReceiptExport($goods_receipt->id), $goods_receipt->reference_number . '.xlsx');
    }

    public function print($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $orders_total = Order::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->sum('total');
        $orders = Order::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', '!=', OrderStatus::INACTIVE)
                    ->get();
        $delivery_receipts = DeliveryReceipt::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', DeliveryReceiptStatus::ACTIVE)
                    ->get();

        return view('admin.goods_receipts.print', compact(
            'delivery_receipts',
            'goods_receipt',
            'orders_total',
            'orders'
        ));
    }

    public function pdf($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $orders_total = Order::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->sum('total');
        $delivery_receipts = DeliveryReceipt::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', DeliveryReceiptStatus::ACTIVE)
                    ->get();
        $query = Order::where('goods_receipt_id', $goods_receipt_id)
                    ->where('status', '!=', OrderStatus::INACTIVE);
                    
        // retreive all records from db
        $data = $query->get();

        // share data to view
        view()->share('data', $data);
        view()->share('orders_total', $orders_total);
        view()->share('goods_receipt', $goods_receipt);
        view()->share('delivery_receipts', $delivery_receipts);

        $pdf = PDF::loadView('admin.goods_receipts.pdf', compact($data));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($goods_receipt->reference_number . '.pdf'); // return as view
        return $pdf->download($goods_receipt->reference_number . '.pdf'); // return as download actual file
    }
}
