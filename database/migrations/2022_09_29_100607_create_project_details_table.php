<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('sub_category_id')->unsigned()->default(0);
            $table->string('name');
            $table->longtext('description');
            $table->integer('qty')->unsigned();
            $table->decimal('usd_rate', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('usd_price', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('internal_price', $precision = 10, $scale = 2);
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->decimal('usd_total', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('internal_total', $precision = 10, $scale = 2);
            $table->decimal('total', $precision = 10, $scale = 2);
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
        Schema::dropIfExists('project_details');
    }
}
