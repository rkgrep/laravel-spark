<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();

            // Two-Factor Authentication Columns...
            $table->string('phone_country_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('two_factor_options')->nullable();

            // Team Columns...
            $table->integer('current_team_id')->nullable();

            // Cashier Columns...
            $table->string('stripe_id')->nullable();
            $table->string('card_brand', 25)->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->text('extra_billing_info')->nullable();

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
        Schema::drop('users');
    }
}
