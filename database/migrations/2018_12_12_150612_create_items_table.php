<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('barcode')->nullable(); // to be used for transaction reference
            $table->string('item_code')->nullable(); // the shops own item id code
            $table->string('name'); // name of the item
            $table->integer('category_id')->unsigned(); // peripherals, components, etc.
            $table->integer('sub_category_id')->nullable()->default(0); // keyboard, mouse, microphone, etc.
            $table->integer('brand_id')->unsigned()->default(1); // default to own shop brand
            $table->string('unit')->default('pc/s'); // pcs, g, kg, ml, etc
            $table->longtext('description')->nullable(); // to be shown to guests
            $table->text('image')->nullable();
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
        Schema::dropIfExists('items');
    }
}
