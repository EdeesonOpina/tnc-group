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
            $table->string('reference_number');
            $table->string('name'); // in payment for
            $table->integer('project_id')->unsigned();
            $table->integer('payment_for_user_id')->unsigned()->default(0);
            $table->integer('payment_for_supplier_id')->unsigned()->default(0);
            $table->date('needed_date');
            $table->decimal('total', $precision = 10, $scale = 2);
            $table->longtext('remarks')->nullable();
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
