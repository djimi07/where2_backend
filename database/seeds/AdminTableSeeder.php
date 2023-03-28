<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_type = 'ADMIN';
        $userData = \App\Models\User::firstOrNew(['userEmail' => 'admin@mailinator.com','user_type' => $user_type]);
        $userData->firstName = 'Bee';
        $userData->lastName = 'Mortgage';
        $userData->userEmail = 'admin@mailinator.com';
        $userData->userMobile = '1234567890';
        $userData->user_type = $user_type;
        $userData->userType = 0;
        $userData->userPassword = bcrypt(123456);
        $userData->save();

       /* $user_type = 'USER';
        $userData = \App\Models\User::firstOrNew(['email' => 'peter@mailinator.com','user_type' => $user_type]);
        $userData->first_name = 'Peter';
        $userData->last_name = 'Parker';
        $userData->email = 'peter@mailinator.com';
        $userData->phone = '1234567891';
        $userData->user_type = $user_type;
        $userData->password = bcrypt(123456);
        $userData->save();*/
    }
}
