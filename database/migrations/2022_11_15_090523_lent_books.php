<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LentBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lent_books', function (Blueprint $table) {
            $table->id();
            $table->string('book_id');
            $table->string('user_id');
            $table->string('lent_date');
            $table->string('return_date');
            $table->string('times_extended');
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
        Schema::dropIfExists('lent_books');
    }
}
