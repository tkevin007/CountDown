<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('TMDB_show_id');
            $table->unsignedBigInteger('show_id');
            $table->unsignedBigInteger('user_id');
            $table->string('show_name');
            $table->integer('season_number');
            $table->integer('episode_number');
            $table->string('episode_name')->nullable();
            $table->string('episode_desc')->nullable();
            $table->string('episode_still_path')->nullable();
            $table->string('user_rating');
            $table->softDeletes();
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
        Schema::dropIfExists('ratings');
    }
}
