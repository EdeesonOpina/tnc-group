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
        $user->position = 'CEO';
        $user->email = 'eric@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->mobile = '123123123';
        $user->role = 'Super Admin';
        $user->company_id = 4;
        $user->password = bcrypt('123123123');
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Ed';
        $user->lastname = 'Opina';
        $user->position = 'IT';
        $user->email = 'ed@tnc.com.ph';
        $user->email_verified_at = Carbon::now();
        $user->mobile = '123123123';
        $user->role = 'Super Admin';
        $user->company_id = 4;
        $user->password = bcrypt('123123123');
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Jam';
        $user->lastname = 'Mauhay';
        $user->email = 'jam@tnc.com.ph';
        $user->position = 'Marketing Commnications Manager';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Sales';
        $user->mobile = '123123123';
        $user->company_id = 1;
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Jim Paulo';
        $user->lastname = 'Sy';
        $user->email = 'jim@tnc.com.ph';
        $user->position = 'Vice President for Operations';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Sales';
        $user->mobile = '123123123';
        $user->company_id = 5;
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Dan Mar';
        $user->lastname = 'Dumawin';
        $user->email = 'danmar@tnc.com.ph';
        $user->position = 'Accounting Staff';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Admin';
        $user->signature = 'uploads/images/signatures/danmar.png';
        $user->mobile = '09208194804';
        $user->company_id = 5;
        $user->status = UserStatus::ACTIVE;
        $user->save();

        $user = new User;
        $user->firstname = 'Aprille';
        $user->lastname = 'Pondales';
        $user->email = 'aprille@tnc.com.ph';
        $user->position = 'Sales';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('123123123');
        $user->role = 'Sales';
        $user->signature = 'uploads/images/signatures/aprille.png';
        $user->mobile = '123123123';
        $user->company_id = 5;
        $user->status = UserStatus::ACTIVE;
        $user->save();
    }
}
