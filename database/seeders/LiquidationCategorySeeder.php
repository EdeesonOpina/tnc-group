<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LiquidationCategory;
use App\Models\LiquidationCategoryStatus;

class LiquidationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new LiquidationCategory;
        $category->name = 'Food';
        $category->status = LiquidationCategoryStatus::ACTIVE;
        $category->save();

        $category = new LiquidationCategory;
        $category->name = 'Transportation';
        $category->status = LiquidationCategoryStatus::ACTIVE;
        $category->save();
    }
}
