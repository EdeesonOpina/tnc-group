<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSerialNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->unsigned();
            $table->integer('cart_id')->unsigned()->default(0);
            $table->integer('payment_id')->unsigned()->default(0); // display on manage serial numbers if who ordered the item with the serial number
            $table->text('code');
            $table->integer('status')->unsigned()->default(1); // meaning item not yet used
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_serial_numbers');
    }
}
