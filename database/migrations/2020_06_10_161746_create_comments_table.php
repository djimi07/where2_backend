<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table)
        {
            $table->bigIncrements('commentId');
            $table->string('userSecret')->default('')->nullable();
            $table->bigInteger('userId');
            $table->bigInteger('restaurantId');
            $table->text('comment');

            $table->timestamp('date')->nullable();

            $table->boolean('status')->default('0')->comment('active or not');
            $table->softDeletes();
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
        Schema::dropIfExists('comments');
    }
}