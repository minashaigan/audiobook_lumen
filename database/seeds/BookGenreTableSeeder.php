<?php

use Illuminate\Database\Seeder;

class BookGenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('book_genre')->insert(['book_id'=>1,'genre_id'=>1]);
         app('db')->table('book_genre')->insert(['book_id'=>1,'genre_id'=>2]);
         app('db')->table('book_genre')->insert(['book_id'=>1,'genre_id'=>3]);
         app('db')->table('book_genre')->insert(['book_id'=>2,'genre_id'=>1]);
         app('db')->table('book_genre')->insert(['book_id'=>2,'genre_id'=>5]);
         app('db')->table('book_genre')->insert(['book_id'=>3,'genre_id'=>4]);
         app('db')->table('book_genre')->insert(['book_id'=>3,'genre_id'=>2]);
         app('db')->table('book_genre')->insert(['book_id'=>3,'genre_id'=>3]);
         app('db')->table('book_genre')->insert(['book_id'=>4,'genre_id'=>4]);
         app('db')->table('book_genre')->insert(['book_id'=>4,'genre_id'=>5]);
    }
}
