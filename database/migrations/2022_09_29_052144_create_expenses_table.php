<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->unsigned();
            $table->integer('company_id')->default(0);
            $table->decimal('cost', $precision = 10, $scale = 2);
            $table->longtext('description')->nullable();
            $table->date('date')->nullable();
            $table->longtext('note')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
