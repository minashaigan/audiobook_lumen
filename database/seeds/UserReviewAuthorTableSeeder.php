<?php

use Illuminate\Database\Seeder;

class UserReviewAuthorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('user_review_author')->insert(['user_id'=>1,'author_id'=>1,'comment'=>'co1','rate'=>1,'enable'=>1]);
         app('db')->table('user_review_author')->insert(['user_id'=>1,'author_id'=>2,'comment'=>'co2','rate'=>2]);
         app('db')->table('user_review_author')->insert(['user_id'=>2,'author_id'=>3,'comment'=>'co3','rate'=>3]);
         app('db')->table('user_review_author')->insert(['user_id'=>2,'author_id'=>1,'comment'=>'co4','rate'=>4,'enable'=>1]);
         app('db')->table('user_review_author')->insert(['user_id'=>2,'author_id'=>5,'comment'=>'co5','rate'=>5,'enable'=>1]);
    }
}
