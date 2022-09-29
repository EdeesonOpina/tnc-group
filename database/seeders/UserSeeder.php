<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserStatus;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->firstname = 'Eric';
        $user->lastname = 'Redulfin';
        $user->email = 'super.admin@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->mobile = '123123123';
        $user->role = 'Super Admin';
        $user->password = bcrypt('123123123');
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Admin';
        $user->lastname = 'Webmaster';
        $user->email = 'admin@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->mobile = '123123123';
        $user->role = 'Super Admin';
        $user->password = bcrypt('123123123');
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Cashier';
        $user->lastname = 'Account';
        $user->email = 'cashier@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Cashier';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Sales';
        $user->lastname = 'Account';
        $user->email = 'sales@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Sales';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Agent';
        $user->lastname = 'Account';
        $user->email = 'agent@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Agent';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'RMA';
        $user->lastname = 'Account';
        $user->email = 'rma@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'RMA';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Accountant';
        $user->lastname = 'Account';
        $user->email = 'accountant@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Accountant';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Encoder';
        $user->lastname = 'Account';
        $user->email = 'encoder@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Encoder';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Customer';
        $user->lastname = 'Account';
        $user->email = 'customer@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Customer';
        $user->mobile = '123123123';
        $user->status = UserStatus::ACTIVE;
        $user->save();
    }
}
