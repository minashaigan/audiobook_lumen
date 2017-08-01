<?php

use Illuminate\Database\Seeder;

class UserGetBookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_get_book')->insert(['user_id'=>1,'book_id'=>4]);
         app('db')->table('user_get_book')->insert(['user_id'=>1,'book_id'=>5]);
         app('db')->table('user_get_book')->insert(['user_id'=>2,'book_id'=>2]);
         app('db')->table('user_get_book')->insert(['user_id'=>2,'book_id'=>3]);
         app('db')->table('user_get_book')->insert(['user_id'=>2,'book_id'=>4]);
    }
}
