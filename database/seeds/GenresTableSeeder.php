<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('genres')->insert(['name' => 'genre1']);
         app('db')->table('genres')->insert(['name' => 'genre2']);
         app('db')->table('genres')->insert(['name' => 'genre3']);
         app('db')->table('genres')->insert(['name' => 'genre4']);
         app('db')->table('genres')->insert(['name' => 'genre5']);
    }
}
