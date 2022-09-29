<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id')->unsigned();
            $table->integer('created_by_user_id')->unsigned();
            $table->integer('received_by_user_id')->default(0);
            $table->string('reference_number');
            $table->longtext('note')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('goods_receipts');
    }
}
