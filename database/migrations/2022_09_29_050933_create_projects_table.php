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
            $table->integer('company_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('name');
            $table->decimal('cost', $precision = 10, $scale = 2);
            $table->date('end_date');
            $table->longtext('description')->nullable();
            $table->bigInteger('created_by_user_id')->default(0);
            $table->bigInteger('noted_by_user_id')->default(0);
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
