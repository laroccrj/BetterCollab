<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song', function (Blueprint $table) {
            $table->id();
            $table->integer('playlist_id');
            $table->index('playlist_id');
            $table->integer('contributor_id');
            $table->index('contributor_id');
            $table->string('spotify_id');
            $table->string('spotify_uri');
            $table->string('title');
            $table->string('artist');
            $table->text('album_art');
            $table->string('album');
            $table->integer('priority');
            $table->index('priority');
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
        Schema::dropIfExists('song');
    }
}
