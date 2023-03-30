<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->bigIncrements('userId');
            $table->string('userSecret')->default('')->nullable();
            $table->string('firstName', 50)->default('')->nullable();
            $table->string('lastName', 50)->default('')->nullable();
            $table->string('userName', 50)->default('')->nullable();
            $table->string('userEmail', 100)->nullable();
            $table->string('userPassword')->default('')->nullable();
            $table->string('userMobile', 15)->default('')->nullable();
            $table->timestamp('userDateOfBirth')->nullable();
            $table->string('userGender', 15)->default('')->nullable();
            $table->boolean('userStatus')->default(1)->comment('bool type(0,1). checks user profile is active or no');
            $table->boolean('active')->default(1)->comment('checks user is currently login(active) or not');
            $table->string('userAddress')->default('')->nullable();
            $table->string('city')->default('')->nullable();
            $table->string('state')->default('')->nullable();
            $table->string('zip_code')->default('')->nullable();
            $table->string('userProfilePicture', 200)->default('')->nullable();
            $table->string('userResetToken')->default('')->nullable();
            $table->tinyInteger('userType')->comment('for chat');
            $table->string('user_type')->comment('for webside');
            $table->string('lastModified', 100)->default('')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp', 6)->default('')->nullable();
            $table->text('gmail_token')->default('')->nullable();
            $table->string('web_token')->nullable();
            $table->string('device_token')->nullable();
            $table->string('fcm_token')->nullable();

            $table->double('latitude', 10, 8)->nullable();
            $table->double('longitude', 10, 8)->nullable();
            $table->boolean('is_owner')->default(0);


            $table->timestamp('login_date')->nullable();
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
        Schema::dropIfExists('users');
    }
}