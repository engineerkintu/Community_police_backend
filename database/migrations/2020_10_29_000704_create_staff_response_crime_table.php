<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffResponseCrimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_response_crime', function (Blueprint $table) {
            $table->id();
            $table->integer('crime');
            $table->integer('user');
            $table->text('response');
            $table->timestamps();

            $table->foreign('crime')->references('id')->on('crimes');
            $table->foreign('user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_response_crime');
    }
}
