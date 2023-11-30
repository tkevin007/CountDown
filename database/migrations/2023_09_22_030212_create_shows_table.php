<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates a new table in the database with the given field names and types
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id(); // The show's Id
            $table->unsignedBigInteger('user_id'); // The user's Id that the record belongs to
            $table->integer('TMDB_show_id'); // The TMDB database's id of this show
            $table->string('status'); // The status of the show 'Returning series' or 'Ended'
            $table->integer('current_season'); // The last seen season by the user
            $table->integer('current_episode'); // The last seen episode by the user
            $table->string('show_name'); // The name of the tv series
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
        Schema::dropIfExists('shows');
    }
}
