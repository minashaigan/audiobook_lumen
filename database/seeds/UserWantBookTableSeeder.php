<?php

use Illuminate\Database\Seeder;

class UserWantBookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_want_book')->insert(['user_id'=>1,'book_id'=>1]);
         app('db')->table('user_want_book')->insert(['user_id'=>1,'book_id'=>2]);
         app('db')->table('user_want_book')->insert(['user_id'=>1,'book_id'=>3]);
         app('db')->table('user_want_book')->insert(['user_id'=>2,'book_id'=>1]);
         app('db')->table('user_want_book')->insert(['user_id'=>2,'book_id'=>5]);
    }
}
