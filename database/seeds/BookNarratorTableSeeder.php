<?php

use Illuminate\Database\Seeder;

class BookNarratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('book_narrator')->insert(['book_id'=>1,'narrator_id'=>1]);
         app('db')->table('book_narrator')->insert(['book_id'=>1,'narrator_id'=>2]);
         app('db')->table('book_narrator')->insert(['book_id'=>1,'narrator_id'=>3]);
         app('db')->table('book_narrator')->insert(['book_id'=>2,'narrator_id'=>1]);
         app('db')->table('book_narrator')->insert(['book_id'=>2,'narrator_id'=>5]);
    }
}
