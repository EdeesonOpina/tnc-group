<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_request_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->unsigned();
            $table->string('name');
            $table->longtext('description');
            $table->integer('qty')->unsigned();
            $table->decimal('price', $precision = 10, $scale = 2);
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
        Schema::dropIfExists('budget_request_forms');
    }
}
