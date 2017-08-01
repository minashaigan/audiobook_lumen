<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('authors')->insert(['name' => 'author1']);
         app('db')->table('authors')->insert(['name' => 'author2','introduction'=>'intro1']);
         app('db')->table('authors')->insert(['name' => 'author3','introduction'=>'intro2','birth_date'=>1200]);
         app('db')->table('authors')->insert(['name' => 'author4','introduction'=>'intro3','birth_date'=>1300,'death_date'=>1400]);
         app('db')->table('authors')->insert(['name' => 'author5','introduction'=>'intro4','birth_date'=>1400,'death_date'=>1500,'nation'=>'nationality']);
    }
}
