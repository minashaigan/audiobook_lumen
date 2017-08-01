<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNarratorGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('narrator_genre', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('narrator_id')->unsigned();
            $table->integer('genre_id')->unsigned();
            $table->unique( array('narrator_id','genre_id') );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('narrator_genre');
    }
}
