<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffResponseComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_response_complaint', function (Blueprint $table) {
            $table->id();
            $table->integer('complaint');
            $table->integer('user');
            $table->text('response');
            $table->timestamps();

            $table->foreign('complaint')->references('id')->on('complaints');
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
        Schema::dropIfExists('staff_response_complaint');
    }
}
