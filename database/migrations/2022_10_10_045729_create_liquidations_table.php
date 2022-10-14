<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidations', function (Blueprint $table) {
            $table->id();
            $table->integer('budget_request_form_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->decimal('cost', $precision = 10, $scale = 2);
            $table->string('name')->nullable();
            $table->longtext('description')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('liquidations');
    }
}
