<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deduction;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deduction = new Deduction;
        $deduction->type = 'VAT';
        $deduction->value = '12'; // in percentage
        $deduction->save();
    }
}
