<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PurchaseOrderExport;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
use Auth;
use Mail;
use Validator;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class PurchaseOrderController extends Controller
{
    public function excel($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);

        return Excel::download(new PurchaseOrderExport($purchase_order->id), $purchase_order->reference_number . '.xlsx');
    }

    public function print($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $orders_total = Order::where('purchase_order_id', $purchase_order_id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->sum('total');
        $orders = Order::where('purchase_order_id', $purchase_order_id)
                    ->where('status', '!=', OrderStatus::INACTIVE)
                    ->get();

        return view('admin.purchase_orders.print', compact(
            'purchase_order',
            'orders_total',
            'orders'
        ));
    }

    public function pdf($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $orders_total = Order::where('purchase_order_id', $purchase_order_id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->sum('total');
        $query = Order::where('purchase_order_id', $purchase_order_id)
                    ->where('status', '!=', OrderStatus::INACTIVE);
                    
        // retreive all records from db
        $data = $query->get();

        // share data to view
        view()->share('data', $data);
        view()->share('orders_total', $orders_total);
        view()->share('purchase_order', $purchase_order);

        $pdf = PDF::loadView('admin.purchase_orders.pdf', compact($data));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($purchase_order->reference_number . '.pdf'); // return as view
        return $pdf->download($purchase_order->reference_number . '.pdf'); // return as view
    }
}
