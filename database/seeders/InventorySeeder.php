<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryStatus;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 1;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 2;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 3;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 4;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 5;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 6;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 7;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 8;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 9;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 10;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 11;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 12;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 13;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 14;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 15;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();

        $inventory = new Inventory;
        $inventory->barcode = rand();
        $inventory->goods_receipt_id = 1;
        $inventory->branch_id = 1;
        $inventory->item_id = 16;
        $inventory->qty = 100;
        $inventory->price = '5000.00';
        $inventory->agent_price = '5000.00';
        $inventory->status = InventoryStatus::ACTIVE;
        $inventory->save();
    }
}
