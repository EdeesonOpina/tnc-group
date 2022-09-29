<?php

namespace App\Exports;

use DB;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderDetail;
use Maatwebsite\Excel\Concerns\WithHeadings; // to add headings
use Maatwebsite\Excel\Concerns\FromCollection; // to use collections
use Illuminate\Contracts\Queue\ShouldQueue; // to be able to queue

class ServiceOrderExport implements FromCollection, WithHeadings, ShouldQueue
{
    // set the parameters here first
    private $jo_number;

    // construct passed parameters here
    function __construct($jo_number) {
        $this->jo_number = $jo_number;
    }

    public function headings():array{
        return[
            'Name',
            'Price',
            'Qty',
            'Total',
            'S/N',
            'Remarks',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $service_order = ServiceOrder::where('jo_number', $this->jo_number)->first();
        $query = ServiceOrderDetail::where('service_order_id', $service_order->id)
                        ->select('name', 'price', 'qty', 'total', 'remarks');

        return $query->get();
    }
}
