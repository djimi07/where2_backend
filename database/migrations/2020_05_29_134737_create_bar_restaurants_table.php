<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_restaurants', function (Blueprint $table)
        {
            $table->bigIncrements('restaurantId');
            $table->bigInteger('ownerId');
            $table->bigInteger('yelpId');
            $table->string('userSecret')->default('')->nullable();
            $table->string('name', 200)->default('')->nullable();
            $table->string('imageUrl', 300)->nullable();
            $table->boolean('status')->default('0')->comment('active or not');
            $table->string('address')->default('')->nullable();
            $table->string('city')->default('')->nullable();
            $table->string('state')->default('')->nullable();
            $table->string('country')->default('')->nullable();
            $table->string('zipCode')->default('')->nullable();
            $table->integer('phone');
            $table->integer('reviewCount');
            $table->float('rating');
            $table->double('latitude', 10, 10);
            $table->double('longitude', 10, 10);
            $table->double('distance', 10, 10);

            $table->boolean('is_bold')->default('0');
            $table->string('color')->default('')->nullable();
            $table->string('fontSize')->default('')->nullable();

            $table->bigInteger('type')->nullable();

            $table->string('description')->default('')->nullable();

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
        Schema::dropIfExists('bar__restaurants');
    }
}
