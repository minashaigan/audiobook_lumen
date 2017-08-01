<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('books')->insert(['name' => 'book1','time'=>'2 ساعت و 26 دقیقه','publisher'=>'publisher','publish_year'=>1396,'file'=>'file1.pdf']);
         app('db')->table('books')->insert(['name' => 'book2','time'=>'3 ساعت و 26 دقیقه','publisher'=>'publisher','publish_year'=>1397,'file'=>'file2.pdf']);
         app('db')->table('books')->insert(['name' => 'book3','time'=>'4 ساعت و 26 دقیقه','publisher'=>'publisher','publish_year'=>1398,'file'=>'file3.pdf']);
         app('db')->table('books')->insert(['name' => 'book4','time'=>'5 ساعت و 26 دقیقه','publisher'=>'publisher','publish_year'=>1399,'file'=>'file4.pdf']);
         app('db')->table('books')->insert(['name' => 'book5','time'=>'6 ساعت و 26 دقیقه','publisher'=>'publisher','publish_year'=>1400,'file'=>'file5.pdf']);
    }
}
