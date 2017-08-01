<?php

use Illuminate\Database\Seeder;

class UserReviewBookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_review_book')->insert(['user_id'=>1,'book_id'=>1,'comment'=>'co1','rate'=>2,'enable'=>1]);
         app('db')->table('user_review_book')->insert(['user_id'=>1,'book_id'=>5,'comment'=>'co2','rate'=>3]);
         app('db')->table('user_review_book')->insert(['user_id'=>2,'book_id'=>2,'comment'=>'co3','rate'=>4,'enable'=>1]);
         app('db')->table('user_review_book')->insert(['user_id'=>2,'book_id'=>3,'comment'=>'co4','rate'=>5]);
         app('db')->table('user_review_book')->insert(['user_id'=>2,'book_id'=>1,'comment'=>'co5','rate'=>1,'enable'=>1]);
    }
}
