<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = new Branch;
        $branch->name = 'TNC - Quezon City';
        $branch->email = 'eric@tnc.com.ph';
        $branch->person = 'Ronalyn Monteras';
        $branch->phone = '712-0089';
        $branch->mobile = '123456789';
        $branch->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $branch->line_address_2 = 'Quezon City';
        $branch->status = 1;
        $branch->save();
    }
}
