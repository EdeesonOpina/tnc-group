<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ReturnInventoryExport;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryStatus;
use App\Models\ReturnInventoryItemStatus;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class RMAController extends Controller
{
    public function excel($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                                        ->first();

        return Excel::download(new ReturnInventoryExport($return_inventory->reference_number), $return_inventory->reference_number . '.xlsx');
    }

    public function print($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                        ->first();
        $branch = Branch::find($return_inventory->branch_id); // branch of that pos
        $user = User::find($return_inventory->user_id); // the customer
        $cashier = User::find($return_inventory->authorized_user_id); // the cashier in charge

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', '!=', ReturnInventoryItemStatus::INACTIVE)
                    ->get();

        return view('admin.rma.print.all', compact(
            'branch',
            'user',
            'cashier',
            'return_inventory',
            'return_inventory_items'
        ));
    }

    public function customer_print($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                        ->first();
        $branch = Branch::find($return_inventory->branch_id); // branch of that pos
        $user = User::find($return_inventory->user_id); // the customer
        $cashier = User::find($return_inventory->authorized_user_id); // the cashier in charge

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', '!=', ReturnInventoryItemStatus::INACTIVE)
                    ->get();

        return view('admin.rma.print.customer', compact(
            'branch',
            'user',
            'cashier',
            'return_inventory',
            'return_inventory_items'
        ));
    }

    public function supplier_print($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                        ->first();
        $branch = Branch::find($return_inventory->branch_id); // branch of that pos
        $user = User::find($return_inventory->user_id); // the customer
        $cashier = User::find($return_inventory->authorized_user_id); // the cashier in charge

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', '!=', ReturnInventoryItemStatus::INACTIVE)
                    ->get();

        return view('admin.rma.print.supplier', compact(
            'branch',
            'user',
            'cashier',
            'return_inventory',
            'return_inventory_items'
        ));
    }

    public function pdf($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                        ->first();
        $branch = Branch::find($return_inventory->branch_id); // branch of that pos
        $user = User::find($return_inventory->payment_receipt->user_id); // the customer
        $rma = User::find($return_inventory->created_by_user_id); // the rma in charge

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', '!=', ReturnInventoryItemStatus::INACTIVE);
                    
        // retreive all records from db
        $data = $return_inventory_items->get();

        // share data to view
        view()->share('data', $data);
        view()->share('return_inventory', $return_inventory);
        view()->share('return_inventory_items', $return_inventory_items);
        view()->share('branch', $branch);
        view()->share('user', $user);
        view()->share('rma', $rma);

        $pdf = PDF::loadView('admin.rma.pdf', compact($data));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($return_inventory->reference_number . '.pdf'); // return as view
        return $pdf->download($return_inventory->reference_number . '.pdf'); // return as view
    }
}
