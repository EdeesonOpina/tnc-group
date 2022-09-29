<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->integer('company_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->bigInteger('created_by_user_id')->default(0);
            $table->bigInteger('approved_by_user_id')->default(0);
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
