<?php

use Illuminate\Database\Seeder;

class NarratorGenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('narrator_genre')->insert(['narrator_id'=>1,'genre_id'=>1]);
         app('db')->table('narrator_genre')->insert(['narrator_id'=>1,'genre_id'=>2]);
         app('db')->table('narrator_genre')->insert(['narrator_id'=>1,'genre_id'=>3]);
         app('db')->table('narrator_genre')->insert(['narrator_id'=>2,'genre_id'=>1]);
         app('db')->table('narrator_genre')->insert(['narrator_id'=>2,'genre_id'=>5]);
    }
}
