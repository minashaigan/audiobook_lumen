<?php

use Illuminate\Database\Seeder;

class NarratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('narrators')->insert(['name' => 'narrator1']);
         app('db')->table('narrators')->insert(['name' => 'narrator2','introduction'=>'intro1']);
    }
}
