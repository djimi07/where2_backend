<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_friends', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('senderId');
            $table->bigInteger('receiverId');

            $table->bigInteger('restaurantId');

            $table->boolean('isAccept')->default(0);
            $table->bigInteger('type')->default(2);
            $table->timestamp('date')->nullable();

            //restaurantId

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
        Schema::dropIfExists('invite_friends');
    }
}