<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagging_tags', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 255)->index();
            $table->string('name', 255);
            $table->boolean('suggest')->default(false);
            $table->integer('count')->unsigned()->default(0); // count of how many times this tag was used
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagging_tags');
    }
}
