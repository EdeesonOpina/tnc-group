<?php

namespace App\Exports;

use DB;
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryItem;
use Maatwebsite\Excel\Concerns\WithHeadings; // to add headings
use Maatwebsite\Excel\Concerns\FromCollection; // to use collections
use Illuminate\Contracts\Queue\ShouldQueue; // to be able to queue

class ReturnInventoryExport implements FromCollection, WithHeadings, ShouldQueue
{
    // set the parameters here first
    private $reference_number;

    // construct passed parameters here
    function __construct($reference_number) {
        $this->reference_number = $reference_number;
    }

    public function headings():array{
        return[
            'Name',
            'Type',
            'Qty',
            'Remarks',
            'Action Taken',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $return_inventory = ReturnInventory::where('reference_number', $this->reference_number)->first();
        $query = ReturnInventoryItem::leftJoin('inventories', 'return_inventory_items.inventory_id', '=', 'inventories.id')
                        ->leftJoin('items', 'inventories.item_id', '=', 'items.id')
                        ->where('return_inventory_items.return_inventory_id', $return_inventory->id)
                        ->select('items.name', 'return_inventory_items.type', 'return_inventory_items.qty', 'return_inventory_items.remarks', 'return_inventory_items.action_taken');

        return $query->get();
    }
}
