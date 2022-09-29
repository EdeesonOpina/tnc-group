<?php

namespace App\Exports;

use DB;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\WithHeadings; // to add headings
use Maatwebsite\Excel\Concerns\FromCollection; // to use collections
use Illuminate\Contracts\Queue\ShouldQueue; // to be able to queue

class PurchaseOrderExport implements FromCollection, WithHeadings, ShouldQueue
{
    // set the parameters here first
    private $purchase_order_id;

    // construct passed parameters here
    function __construct($purchase_order_id) {
        $this->purchase_order_id = $purchase_order_id;
    }

    public function headings():array{
        return[
            'Id',
            'Item',
            'Brand',
            'Category',
            'Price',
            'Discount',
            'Qty',
            'Free Qty',
            'Total',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Order::where('purchase_order_id', $this->purchase_order_id)
                ->leftJoin('items', 'orders.item_id', '=', 'items.id')
                ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                ->select('orders.id', 'items.name as item_name', 'brands.name as brand_name', 'categories.name as category_name', 'orders.price', 'orders.discount', 'orders.qty', 'orders.free_qty', 'orders.total');

        return $query->get();
    }
}
