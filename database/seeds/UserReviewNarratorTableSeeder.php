<?php

use Illuminate\Database\Seeder;

class UserReviewNarratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_review_narrator')->insert(['user_id'=>1,'narrator_id'=>1,'comment'=>'co1','rate'=>1,'enable'=>1]);
         app('db')->table('user_review_narrator')->insert(['user_id'=>1,'narrator_id'=>2,'comment'=>'co2','rate'=>2,'enable'=>1]);
         app('db')->table('user_review_narrator')->insert(['user_id'=>2,'narrator_id'=>2,'comment'=>'co3','rate'=>3,'enable'=>1]);
         app('db')->table('user_review_narrator')->insert(['user_id'=>2,'narrator_id'=>1,'comment'=>'co4','rate'=>4,'enable'=>1]);
    }
}
