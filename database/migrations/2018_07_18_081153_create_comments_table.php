<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('video_url');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('video_url')->references('url')->on('videos');
            $table->text('comment_text');
            $table->datetime('posted');
            $table->integer('parent_comment_id')->nullable();
            $table->foreign('parent_comment_id')->references('id')->on('comments');
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
        Schema::dropIfExists('comments');
    }
}
