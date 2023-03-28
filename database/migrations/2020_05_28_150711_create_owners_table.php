<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->bigIncrements('ownerId');
            $table->string('ownerSecret')->default('')->nullable();
            $table->string('firstName',50)->default('')->nullable();
            $table->string('lastName',50)->default('')->nullable();
            $table->string('userName',50)->default('')->nullable();
            $table->string('ownerEmail',100)->nullable();
            $table->string('onwerPassword')->default('')->nullable();
            $table->string('onwerMobile',15)->default('')->nullable();
            $table->boolean('onwerStatus')->default(0)->comment('1 for block or 0 for unblock');
            $table->string('profilePicture',50)->default('')->nullable();
            $table->string('lastModified',100)->default('')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp',6)->default('')->nullable();
            $table->text('gmail_token')->default('')->nullable();
            $table->string('web_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('owners');
    }
}
