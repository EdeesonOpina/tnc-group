<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category;
        $category->name = 'Gamer Pack';
        $category->description = 'Gamer Pack';
        $category->is_package = 1;
        $category->save();

        $category = new Category;
        $category->name = 'Streamer Pack';
        $category->description = 'Streamer Pack';
        $category->is_package = 1;
        $category->save();

        $category = new Category;
        $category->name = 'Components';
        $category->description = 'Components';
        $category->save();

        $category = new Category;
        $category->name = 'Peripherals';
        $category->description = 'Peripherals';
        $category->save();

        $category = new Category;
        $category->name = 'Gaming Consoles';
        $category->description = 'Gaming Consoles';
        $category->save();

        $category = new Category;
        $category->name = 'Softwares';
        $category->description = 'Softwares';
        $category->save();

        $category = new Category;
        $category->name = 'Security';
        $category->description = 'Security';
        $category->save();

        $category = new Category;
        $category->name = 'Accessories';
        $category->description = 'Accessories';
        $category->save();

        $category = new Category;
        $category->name = 'Phoenix Apparel';
        $category->description = 'Phoenix Apparel';
        $category->save();
    }
}
