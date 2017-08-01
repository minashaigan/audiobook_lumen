<?php

use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('sections')->insert(['chapter_number' => '1','chapter_name'=>'chap1','book_id'=>'1','time'=>'8 ساعت و 30 دقیقه','file'=>'chap_file1.pdf']);
         app('db')->table('sections')->insert(['chapter_number' => '2','chapter_name'=>'chap2','book_id'=>'1','time'=>'9 ساعت و 30 دقیقه','file'=>'chap_file2.pdf']);
         app('db')->table('sections')->insert(['chapter_number' => '3','chapter_name'=>'chap3','book_id'=>'1','time'=>'10 ساعت و 30 دقیقه','file'=>'chap_file3.pdf']);
         app('db')->table('sections')->insert(['chapter_number' => '4','chapter_name'=>'chap4','book_id'=>'1','time'=>'11 ساعت و 30 دقیقه','file'=>'chap_file4.pdf']);
         app('db')->table('sections')->insert(['chapter_number' => '5','chapter_name'=>'chap5','book_id'=>'1','time'=>'12 ساعت و 30 دقیقه','file'=>'chap_file5.pdf']);
         app('db')->table('sections')->insert(['chapter_number' => '1','chapter_name'=>'chap1','book_id'=>'2']);
         app('db')->table('sections')->insert(['chapter_number' => '2','chapter_name'=>'chap2','book_id'=>'2']);
         app('db')->table('sections')->insert(['chapter_number' => '3','chapter_name'=>'chap3','book_id'=>'2']);
         app('db')->table('sections')->insert(['chapter_number' => '4','chapter_name'=>'chap4','book_id'=>'2']);
         app('db')->table('sections')->insert(['chapter_number' => '5','chapter_name'=>'chap5','book_id'=>'2']);
         app('db')->table('sections')->insert(['chapter_number' => '1','chapter_name'=>'chap1','book_id'=>'3']);
         app('db')->table('sections')->insert(['chapter_number' => '2','chapter_name'=>'chap2','book_id'=>'3']);
         app('db')->table('sections')->insert(['chapter_number' => '3','chapter_name'=>'chap3','book_id'=>'3']);
         app('db')->table('sections')->insert(['chapter_number' => '4','chapter_name'=>'chap4','book_id'=>'3']);
         app('db')->table('sections')->insert(['chapter_number' => '5','chapter_name'=>'chap5','book_id'=>'3']); 
    }
}
