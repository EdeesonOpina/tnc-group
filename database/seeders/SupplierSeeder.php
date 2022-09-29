<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\SupplierStatus;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Supplier;
        $supplier->name = 'Supplier A';
        $supplier->email = 'supplier.a@test.com';
        $supplier->person = 'Person A';
        $supplier->phone = '123-456789';
        $supplier->mobile = '123456789';
        $supplier->line_address_1 = 'Supplier Address A1';
        $supplier->line_address_2 = 'Supplier Address A2';
        $supplier->status = SupplierStatus::ACTIVE;
        $supplier->save();

        $supplier = new Supplier;
        $supplier->name = 'Supplier B';
        $supplier->email = 'supplier.b@test.com';
        $supplier->person = 'Person B';
        $supplier->phone = '123-456789';
        $supplier->mobile = '123456789';
        $supplier->line_address_1 = 'Supplier Address B1';
        $supplier->line_address_2 = 'Supplier Address B2';
        $supplier->status = SupplierStatus::ACTIVE;
        $supplier->save();

        $supplier = new Supplier;
        $supplier->name = 'Supplier C';
        $supplier->email = 'supplier.c@test.com';
        $supplier->person = 'Person C';
        $supplier->phone = '123-456789';
        $supplier->mobile = '123456789';
        $supplier->line_address_1 = 'Supplier Address C1';
        $supplier->line_address_2 = 'Supplier Address C2';
        $supplier->status = SupplierStatus::ACTIVE;
        $supplier->save();
    }
}
