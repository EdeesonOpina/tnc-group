<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\Inventory;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventory = Inventory::find(1);

        $cart = new Cart;
        $cart->inventory_id = 1;
        $cart->item_id = $inventory->id;
        $cart->user_id = 1;
        $cart->authorized_user_id = 1;
        $cart->qty = 1;
        $cart->price = $inventory->price;
        $cart->total = $inventory->price * 1;
        $cart->save();

        $inventory = Inventory::find(1);

        $cart = new Cart;
        $cart->inventory_id = 2;
        $cart->item_id = $inventory->id;
        $cart->user_id = 1;
        $cart->authorized_user_id = 1;
        $cart->qty = 2;
        $cart->price = $inventory->price;
        $cart->total = $inventory->price * 2;
        $cart->save();
    }
}
