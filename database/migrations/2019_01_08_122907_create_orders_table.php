<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id')->unsigned();
            $table->integer('goods_receipt_id')->unsigned()->default(0);
            $table->integer('performed_by_user_id')->default(0);
            $table->integer('supply_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('qty')->unsigned()->default(0);
            $table->integer('free_qty')->unsigned()->default(0);
            $table->integer('received_qty')->unsigned()->default(0);
            $table->decimal('discount', $precision = 10, $scale = 2)->default(0);
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->decimal('total', $precision = 10, $scale = 2);
            $table->longtext('note')->nullable();
            $table->integer('status')->unsigned()->default(1);
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
        Schema::dropIfExists('orders');
    }
}
