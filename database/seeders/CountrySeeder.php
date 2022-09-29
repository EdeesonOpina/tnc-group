<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = new Country;
        $country->name = 'Philippines';
        $country->save();

        $country = new Country;
        $country->name = 'USA';
        $country->save();

        $country = new Country;
        $country->name = 'China';
        $country->save();
    }
}
