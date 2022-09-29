<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = new Account;
        $account->bank = 'Cash On Delivery';
        $account->number = 'Upon Arrival';
        $account->name = 'COD';
        $account->save();

        $account = new Account;
        $account->bank = 'Paypal';
        $account->number = 'Online';
        $account->name = 'Paypal';
        $account->save();

        $account = new Account;
        $account->bank = 'BDO';
        $account->number = '003880151274';
        $account->name = 'Big Four Global Technologies Inc.';
        $account->save();

        $account = new Account;
        $account->bank = 'Metrobank';
        $account->number = '705-7705007370';
        $account->name = 'THENET.COM INC.';
        $account->save();

        $account = new Account;
        $account->bank = 'Chinabank';
        $account->number = '132900002800';
        $account->name = 'THENET.COM.INC';
        $account->save();
    }
}
