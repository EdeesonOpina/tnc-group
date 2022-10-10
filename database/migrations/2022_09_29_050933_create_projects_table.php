<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->integer('company_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('name');
            $table->decimal('total', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('internal_total', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('asf', $precision = 10, $scale = 2)->default('0.00');
            $table->decimal('vat', $precision = 10, $scale = 2)->default('0.00');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('duration_date')->nullable();
            $table->longtext('description')->nullable();
            $table->longtext('proposal_ownership')->nullable();
            $table->longtext('confidentiality')->nullable();
            $table->longtext('project_confirmation')->nullable();
            $table->longtext('payment_terms')->nullable();
            $table->longtext('validity')->nullable();
            $table->bigInteger('prepared_by_user_id')->default(0);
            $table->bigInteger('noted_by_user_id')->default(0);
            $table->bigInteger('client_contact_id')->default(0); // conforme
            $table->bigInteger('created_by_user_id')->default(0);
            $table->bigInteger('approved_by_user_id')->default(0);
            $table->integer('status')->unsigned()->default(1);
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
