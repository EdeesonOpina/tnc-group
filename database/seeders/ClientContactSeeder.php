<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientContact;

class ClientContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client_contact = new ClientContact;
        $client_contact->client_id = 1;
        $client_contact->name = 'Princess Lao';
        $client_contact->email = 'princess.lao@acer.com.ph';
        $client_contact->position = 'Senior Marketing Manager';
        $client_contact->phone = '712-0089';
        $client_contact->mobile = '123456789';
        $client_contact->line_address_1 = '11 N. Roxas St. Brgy. San Isidro Labrador';
        $client_contact->line_address_2 = 'Quezon City';
        $client_contact->status = 1;
        $client_contact->save();
    }
}
