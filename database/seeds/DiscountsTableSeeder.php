<?php

use Illuminate\Database\Seeder;

class DiscountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         app('db')->table('discounts')->insert(['code' => 'code1','count'=>10,'type'=>0,'value'=>10,'subscription_id'=>1]);
         app('db')->table('discounts')->insert(['code' => 'code2','count'=>20,'type'=>0,'value'=>11,'subscription_id'=>2]);
         app('db')->table('discounts')->insert(['code' => 'code3','count'=>30,'type'=>0,'value'=>12,'subscription_id'=>3]);
         app('db')->table('discounts')->insert(['code' => 'code4','count'=>40,'type'=>1,'value'=>40,'subscription_id'=>4]);
         app('db')->table('discounts')->insert(['code' => 'code5','count'=>50,'type'=>1,'value'=>50,'subscription_id'=>5]);
         app('db')->table('discounts')->insert(['code' => 'code6','count'=>10,'type'=>0,'value'=>10,'subscription_id'=>1]);
         app('db')->table('discounts')->insert(['code' => 'code7','count'=>20,'type'=>0,'value'=>11,'subscription_id'=>1]);
         app('db')->table('discounts')->insert(['code' => 'code8','count'=>30,'type'=>0,'value'=>12,'subscription_id'=>1]);
         app('db')->table('discounts')->insert(['code' => 'code9','count'=>40,'type'=>1,'value'=>40,'subscription_id'=>2]);
         app('db')->table('discounts')->insert(['code' => 'code10','count'=>50,'type'=>1,'value'=>50,'subscription_id'=>2]);
    }
}
