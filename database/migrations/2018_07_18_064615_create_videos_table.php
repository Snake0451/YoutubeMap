<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('youtube_id')->unique();
            $table->string('title')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->datetime('time_published');
            $table->tinyInteger('emotion_id')->unsigned()->nullable()->default(null);
            $table->foreign('emotion_id')->references('id')->on('emotions');
            $table->integer('event_id')->unsigned()->nullable()->default(null)->onDelete('null');
            $table->foreign('event_id')->references('id')->on('events');
            $table->float('latitude', 10, 7);
            $table->float('longitude', 10, 7);
            $table->string('author');
            $table->string('authors_avatar')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
