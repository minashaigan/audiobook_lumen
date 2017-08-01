<?php

use Illuminate\Database\Seeder;

class UserGenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_genre')->insert(['user_id'=>1,'genre_id'=>1]);
         app('db')->table('user_genre')->insert(['user_id'=>1,'genre_id'=>2]);
         app('db')->table('user_genre')->insert(['user_id'=>1,'genre_id'=>3]);
         app('db')->table('user_genre')->insert(['user_id'=>2,'genre_id'=>1]);
         app('db')->table('user_genre')->insert(['user_id'=>2,'genre_id'=>5]);
    }
}
