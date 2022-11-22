<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
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
            $table->string('name');
            $table->string('middlename')->nullable();
            $table->string('surname');
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('adres');
            $table->string('postcode');
            $table->string('city');
            $table->integer('rol_id');
            $table->string('date_employed')->nullable();
            $table->integer('subscription_id')->nullable();
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
