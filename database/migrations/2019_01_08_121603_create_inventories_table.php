<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            
            // get the last transaction of goods receipt for this one
            $table->integer('goods_receipt_id')->nullable();
            
            $table->integer('company_id')->unsigned();
            $table->integer('item_id')->unsigned();

            $table->integer('qty')->default(0);
            $table->decimal('price', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('agent_price', $precision = 10, $scale = 2)->default(0.00);
            $table->date('discount_from_date')->nullable();
            $table->date('discount_to_date')->nullable();
            $table->string('discount', $precision = 10, $scale = 2)->default(0);
            $table->decimal('points', $precision = 10, $scale = 2)->default(0); // points per purchase

            $table->integer('views')->default(0);

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
        Schema::dropIfExists('inventories');
    }
}
