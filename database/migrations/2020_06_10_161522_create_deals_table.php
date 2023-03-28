<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table)
        {
            $table->bigIncrements('dealId');
            $table->string('userSecret')->default('')->nullable();
            $table->bigInteger('restaurantId');
            $table->string('offer', 200)->default('')->nullable();
            $table->string('category', 200)->default('')->nullable();
            $table->string('imageUrl', 100)->nullable();

            $table->string('description')->default('')->nullable();
            $table->string('hot_deal')->default('')->nullable();
            $table->string('eventType')->default('')->nullable();

            $table->string('title')->default('')->nullable();
            $table->string('eventName')->default('')->nullable();
            $table->string('bogo')->default('')->nullable();
            $table->string('status')->default('')->nullable();
            $table->timestamp('enddate')->nullable();

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
        Schema::dropIfExists('deals');
    }
}