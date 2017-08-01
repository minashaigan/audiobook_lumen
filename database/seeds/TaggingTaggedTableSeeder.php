<?php

use Illuminate\Database\Seeder;

class TaggingTaggedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Book','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Book','tag_name'=>'TAG2','tag_slug'=>'tag2']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Book','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>3,'taggable_type'=>'App\Book','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Book','tag_name'=>'TAG2','tag_slug'=>'tag2']);

        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Author','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Author','tag_name'=>'TAG2','tag_slug'=>'tag2']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Author','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>3,'taggable_type'=>'App\Author','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Author','tag_name'=>'TAG2','tag_slug'=>'tag2']);

        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Narrator','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>1,'taggable_type'=>'App\Narrator','tag_name'=>'TAG2','tag_slug'=>'tag2']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Narrator','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>3,'taggable_type'=>'App\Narrator','tag_name'=>'TAG1','tag_slug'=>'tag1']);
        app('db')->table('tagging_tagged')->insert(['taggable_id'=>2,'taggable_type'=>'App\Narrator','tag_name'=>'TAG2','tag_slug'=>'tag2']);
    }
}
