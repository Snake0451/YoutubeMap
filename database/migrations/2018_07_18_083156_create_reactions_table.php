<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('video_url');
            $table->integer('comment_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('video_url')->references('url')->on('videos');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->boolean('is_emotion');
            $table->smallInteger('emotion_id')->nullable();
            $table->foreign('emotion_id')->references('id')->on('emotions');
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
        Schema::dropIfExists('reactions');
    }
}
