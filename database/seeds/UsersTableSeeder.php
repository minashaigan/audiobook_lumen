<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('users')->insert(['name' => 'user1','email'=>'user1@yahoo.com','password'=>'123456','activated'=>'1','api_token'=>'Ur2jwparrXpPO5SsVwr1XuNlK5BlMu0X2k2KkJQM30PDpeCPaaH5lWgBpPC9']);
        app('db')->table('users')->insert(['name' => 'user2','email'=>'user2@yahoo.com','password'=>'123456','activated'=>'0','api_token'=>'Ur2jwparrXpPO5SsVwr1XuNlK5BlMu0X2k2KkJQM30PDpeCPaaH5lWgBpPC9111']);
    }
}
