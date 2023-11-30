<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * Creates a new table in the database with the given field names and types
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // The User's ID
            $table->string('username')->unique(); // Username
            $table->string('email')->unique(); // Email adress
            $table->string('password'); // Password
            $table->string('role'); // The users role can be 'User' or 'Admin'
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
