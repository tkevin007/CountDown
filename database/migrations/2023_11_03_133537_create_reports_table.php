<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates a new table in the database with the given field names and types
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id'); // The sending user's id
            $table->string('message'); // The message that's being sent
            $table->string('type'); // The type of the message "Message" or "Report"
            $table->boolean('read'); // An admin can switch between true and false if the type of the message is "Message"
            $table->string('status'); // An admin can switch beteween "Fixed" and "Unfixed" if the type of the message is "Report"
            $table->string('lastModifier')->nullable(); //The last user's id who changed either the read or status column's value
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
        Schema::dropIfExists('reports');
    }
}
