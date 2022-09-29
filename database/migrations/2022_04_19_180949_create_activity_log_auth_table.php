<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_auth', function (Blueprint $table) {
            $table->id();
            $table->text('ip_address');
            $table->text('device');
            $table->text('uri');
            $table->integer('auth_id')->unsigned()->default(0);
            $table->longtext('description')->nullable();
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
        Schema::dropIfExists('activity_log_auth');
    }
}
