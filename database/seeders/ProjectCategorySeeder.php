<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryStatus;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new ProjectCategory;
        $category->name = 'Talent Management';
        $category->status = ProjectCategoryStatus::ACTIVE;
        $category->save();

        $category = new ProjectCategory;
        $category->name = 'Social Media';
        $category->status = ProjectCategoryStatus::ACTIVE;
        $category->save();
    }
}
