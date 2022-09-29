<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        // $this->call(ItemSeeder::class);
        // $this->call(BrandSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(CountrySeeder::class);
        // $this->call(SupplierSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(InventorySeeder::class);
        $this->call(DeductionSeeder::class);
        // $this->call(SubCategorySeeder::class);

        // $this->call(CartSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
