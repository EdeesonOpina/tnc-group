<?php

namespace App\Exports;

use DB;
use App\Models\Payment;
use App\Models\GoodsReceipt;
use Maatwebsite\Excel\Concerns\WithHeadings; // to add headings
use Maatwebsite\Excel\Concerns\FromCollection; // to use collections
use Illuminate\Contracts\Queue\ShouldQueue; // to be able to queue

class PaymentExport implements FromCollection, WithHeadings, ShouldQueue
{
    // set the parameters here first
    private $so_number;

    // construct passed parameters here
    function __construct($so_number) {
        $this->so_number = $so_number;
    }

    public function headings():array{
        return[
            'Item',
            'Brand',
            'Category',
            'Price',
            'Discount',
            'Qty',
            'Total',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Payment::where('so_number', $this->so_number)
                ->leftJoin('items', 'payments.item_id', '=', 'items.id')
                ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                ->select('items.name as item_name', 'brands.name as brand_name', 'categories.name as category_name', 'payments.price', 'payments.discount', 'payments.qty', 'payments.total');

        return $query->get();
    }
}
