<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates a new table in the database with the given field names and types
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // The rating's id
            $table->unsignedBigInteger('show_id'); // The show this rating belongs to
            $table->unsignedBigInteger('user_id'); // The user this rating belongs to
            $table->integer('TMDB_show_id'); // The TMDB database's id of this show
            $table->string('show_name'); // The name of the rated show
            $table->integer('season_number'); // The season number of the rated show
            $table->integer('episode_number'); // The episode number of the rated show
            $table->string('episode_name')->nullable(); // The name of the episode
            $table->string('episode_desc')->nullable(); // The description of the episode
            $table->string('episode_still_path')->nullable(); // The path to an image from the rated episode
            $table->integer('user_rating'); // The users rating of the episode
            $table->softDeletes(); // Handles the soft deletion of the records
            $table->timestamps(); // Adds Created_at, Updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
