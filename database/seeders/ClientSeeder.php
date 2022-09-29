<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client;
        $client->name = 'Eric';
        $client->email = 'eric@tnc.com.ph';
        $client->person = 'Eric Redulfin';
        $client->phone = '712-0089';
        $client->mobile = '123456789';
        $client->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $client->line_address_2 = 'Quezon City';
        $client->status = 1;
        $client->save();
    }
}
