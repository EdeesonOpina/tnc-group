<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brand = new Brand;
        $brand->name = 'TNC';
        $brand->image = env('APP_ICON_WHITE');
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Acer';
        $brand->image = 'front/img/brands/1.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'AMD';
        $brand->image = 'front/img/brands/2.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'ASUS';
        $brand->image = 'front/img/brands/3.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Corsair';
        $brand->image = 'front/img/brands/4.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Gigabyte';
        $brand->image = 'front/img/brands/5.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'HyperX';
        $brand->image = 'front/img/brands/6.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Intel';
        $brand->image = 'front/img/brands/7.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'MSI';
        $brand->image = 'front/img/brands/8.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Samsung';
        $brand->image = 'front/img/brands/9.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'TeamGroup';
        $brand->image = 'front/img/brands/10.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'TP-Link';
        $brand->image = 'front/img/brands/11.png';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Kingston';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Crucial';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Kingmax';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Apacer';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Corsair';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'TG / TUF';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Gskill';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Klevv';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'OCPC';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Asrock';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Galax';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Biostar';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'N-Vision';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Phillips';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Viewplus';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Viewsonic';
        $brand->save();

        $brand = new Brand;
        $brand->name = 'Hikconnect';
        $brand->save();
    }
}
