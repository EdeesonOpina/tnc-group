<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryReturnRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_return_records', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_receipt_id')->unsigned();
            $table->integer('inventory_id')->unsigned();
            $table->integer('user_id')->unsigned(); // the one who made an action
            $table->integer('qty')->unsigned();
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->decimal('discount', $precision = 10, $scale = 2);
            $table->decimal('total', $precision = 10, $scale = 2);
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
        Schema::dropIfExists('inventory_return_records');
    }
}
