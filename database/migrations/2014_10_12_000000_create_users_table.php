<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('corporate')->nullable();
            
            $table->string('position')->nullable();

            $table->string('phone')->nullable();
            $table->string('mobile');
            
            $table->string('line_address_1')->nullable();
            $table->string('line_address_2')->nullable();

            $table->integer('company_id')->unsigned()->default(1); // employee assigned branch
            $table->string('role'); // if employee or corporate
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->longtext('biography')->nullable();
            $table->text('avatar')->nullable();
            $table->text('signature')->nullable(); // for employee signature

            $table->integer('status')->default(1); // default as pending
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
