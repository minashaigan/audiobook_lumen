<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tagging_tagged', function(Blueprint $table) {
            $table->increments('id');
            if(config('tagging.primary_keys_type') == 'string') {
                $table->string('taggable_id', 36)->index();
            } else {
                $table->integer('taggable_id')->unsigned()->index();
            }
            $table->string('taggable_type', 255)->index();
            $table->string('tag_name', 255);
            $table->string('tag_slug', 255)->index();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagging_tagged');
    }
}
