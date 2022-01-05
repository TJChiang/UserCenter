<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('line_id', 100)->nullable();
            // $table->morphs('tokenable');
            $table->string('token_type', 10)->nullable();
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            // $table->string('email')->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
