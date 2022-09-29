<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* gamer pack */
        $sub_category = new SubCategory;
        $sub_category->category_id = 1;
        $sub_category->name = 'Starter Gaming Package';
        $sub_category->is_package = 1;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 1;
        $sub_category->name = 'Professional Gaming Package';
        $sub_category->is_package = 1;
        $sub_category->save();

        /* streamer pack */
        $sub_category = new SubCategory;
        $sub_category->category_id = 2;
        $sub_category->name = 'Starter Streaming Package';
        $sub_category->is_package = 1;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 2;
        $sub_category->name = 'Professional Streaming Package';
        $sub_category->is_package = 1;
        $sub_category->save();

        /* components */
        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Processor';
        $sub_category->sort_order = 2;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Motherboard';
        $sub_category->sort_order = 1;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Graphic Cards';
        $sub_category->sort_order = 6;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Memory Modules';
        $sub_category->sort_order = 3;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Power Supply';
        $sub_category->sort_order = 7;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Internal Storage';
        $sub_category->sort_order = 4;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Optical Drive';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'NIC Cards';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'CPU Cooling';
        $sub_category->sort_order = 5;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 3;
        $sub_category->name = 'Casing';
        $sub_category->sort_order = 8;
        $sub_category->save();

        /* peripherals */
        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Mouse';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Keyboard';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Keyboard + Mouse';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Printer';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Scanner';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Storage Device';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Powerbank';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Monitor';    
        $sub_category->sort_order = 9;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Headset';
        $sub_category->sort_order = 10;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Camera';
        $sub_category->sort_order = 11;
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Speakers';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 4;
        $sub_category->name = 'Cable';
        $sub_category->save();

        /* gaming */
        $sub_category = new SubCategory;
        $sub_category->category_id = 5;
        $sub_category->name = 'Console';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 5;
        $sub_category->name = 'Controllers';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 5;
        $sub_category->name = 'Handheld';
        $sub_category->save();

        /* softwares */
        $sub_category = new SubCategory;
        $sub_category->category_id = 6;
        $sub_category->name = 'Security Applications';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 6;
        $sub_category->name = 'Microsoft Office';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 6;
        $sub_category->name = 'Operating System';
        $sub_category->save();

        /* security */
        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'CCTV';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'NVR';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'Wireless Kit';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'Wireless NVR';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'DVR Kit';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'PoE Switches';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 7;
        $sub_category->name = 'UTP Cable';
        $sub_category->save();

        /* accessories */
        $sub_category = new SubCategory;
        $sub_category->category_id = 8;
        $sub_category->name = 'Gaming Chair';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 8;
        $sub_category->name = 'Office Chair';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 8;
        $sub_category->name = 'Table';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 8;
        $sub_category->name = 'Headset';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 8;
        $sub_category->name = 'Camera';
        $sub_category->save();

        /* apparel */
        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Shirt';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Pants';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Jacket';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Mask';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Shorts';
        $sub_category->save();

        $sub_category = new SubCategory;
        $sub_category->category_id = 9;
        $sub_category->name = 'Accessories';
        $sub_category->save();
    }
}
