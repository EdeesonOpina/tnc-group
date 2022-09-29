<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemStatus;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = new Item;
        $item->name = 'Item A';
        $item->brand_id = 1;
        $item->category_id = 1;
        $item->sub_category_id = 1;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/1.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item B';
        $item->brand_id = 2;
        $item->category_id = 2;
        $item->sub_category_id = 2;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/2.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item C';
        $item->brand_id = 3;
        $item->category_id = 3;
        $item->sub_category_id = 3;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/3.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item D';
        $item->brand_id = 4;
        $item->category_id = 4;
        $item->sub_category_id = 4;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/4.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item D1';
        $item->brand_id = 4;
        $item->category_id = 4;
        $item->sub_category_id = 4;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/5.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item D2';
        $item->brand_id = 4;
        $item->category_id = 4;
        $item->sub_category_id = 4;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/6.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item D3';
        $item->brand_id = 4;
        $item->category_id = 4;
        $item->sub_category_id = 4;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/7.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item D4';
        $item->brand_id = 4;
        $item->category_id = 4;
        $item->sub_category_id = 4;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/8.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item E';
        $item->brand_id = 5;
        $item->category_id = 5;
        $item->sub_category_id = 5;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/9.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item E1';
        $item->brand_id = 5;
        $item->category_id = 5;
        $item->sub_category_id = 5;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/10.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item E2';
        $item->brand_id = 5;
        $item->category_id = 5;
        $item->sub_category_id = 5;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/11.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item E3';
        $item->brand_id = 5;
        $item->category_id = 5;
        $item->sub_category_id = 5;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/12.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item E4';
        $item->brand_id = 5;
        $item->category_id = 5;
        $item->sub_category_id = 5;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/13.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item F';
        $item->brand_id = 6;
        $item->category_id = 6;
        $item->sub_category_id = 6;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/14.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item F1';
        $item->brand_id = 6;
        $item->category_id = 6;
        $item->sub_category_id = 6;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/15.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item F2';
        $item->brand_id = 6;
        $item->category_id = 6;
        $item->sub_category_id = 6;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/16.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item F3';
        $item->brand_id = 6;
        $item->category_id = 6;
        $item->sub_category_id = 6;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/2.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();

        $item = new Item;
        $item->name = 'Item F4';
        $item->brand_id = 6;
        $item->category_id = 6;
        $item->sub_category_id = 6;
        $item->description = 'This is just a test';
        $item->image = 'front/img/items/3.jpg';
        $item->status = ItemStatus::ACTIVE;
        $item->save();
    }
}
