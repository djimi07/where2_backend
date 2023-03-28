<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckedinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkedins', function (Blueprint $table)
        {
            $table->bigIncrements('checkinId');
            $table->bigInteger('userId');
            $table->bigInteger('restaurantId');
            $table->boolean('status')->default('0')->comment('1 checkedIn or 0 checkout');
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('checkedins');
    }
}