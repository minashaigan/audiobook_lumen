<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagging_tags', function ($table) {
            $table->integer('tag_group_id')->unsigned()->nullable()->after('id');
            $table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagging_tags', function ($table) {
            $table->dropForeign('tagging_tags_tag_group_id_foreign');
            $table->dropColumn('tag_group_id');
        });
    }
}
