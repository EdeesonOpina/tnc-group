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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('company')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile');

            $table->string('company_id')->default(1);
            $table->string('line_address_1')->nullable();
            $table->string('line_address_2')->nullable();

            $table->integer('branch_id')->unsigned()->default(1); // employee assigned branch
            $table->string('role'); // if cashier, agent, admin, or customer
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('facebook')->nullable();
            $table->string('google')->nullable();
            $table->string('provider')->nullable(); // oauth name
            $table->string('provider_id')->nullable(); // oauth id

            $table->text('avatar')->nullable();
            $table->text('signature')->nullable(); // for employee signature
            $table->decimal('points', $precision = 10, $scale = 2)->default(0); // points per purchase
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
