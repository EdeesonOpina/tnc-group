<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = new Company;
        $company->name = 'Digimart';
        $company->email = 'eric@tnc.com.ph';
        $company->person = 'Eric Redulfin';
        $company->phone = '712-0089';
        $company->mobile = '123456789';
        $company->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $company->line_address_2 = 'Quezon City';
        $company->status = 1;
        $company->save();

        $company = new Company;
        $company->name = 'TNC Production';
        $company->email = 'eric@tnc.com.ph';
        $company->person = 'Eric Redulfin';
        $company->phone = '712-0089';
        $company->mobile = '123456789';
        $company->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $company->line_address_2 = 'Quezon City';
        $company->status = 1;
        $company->save();

        $company = new Company;
        $company->name = 'TNC Proteam';
        $company->email = 'eric@tnc.com.ph';
        $company->person = 'Eric Redulfin';
        $company->phone = '712-0089';
        $company->mobile = '123456789';
        $company->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $company->line_address_2 = 'Quezon City';
        $company->status = 1;
        $company->save();

        $company = new Company;
        $company->name = 'TheNet.com';
        $company->email = 'eric@tnc.com.ph';
        $company->person = 'Eric Redulfin';
        $company->phone = '712-0089';
        $company->mobile = '123456789';
        $company->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $company->line_address_2 = 'Quezon City';
        $company->status = 1;
        $company->save();

        $company = new Company;
        $company->name = 'TNC Group';
        $company->email = 'eric@tnc.com.ph';
        $company->person = 'Eric Redulfin';
        $company->phone = '712-0089';
        $company->mobile = '123456789';
        $company->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $company->line_address_2 = 'Quezon City';
        $company->status = 1;
        $company->save();
    }
}
