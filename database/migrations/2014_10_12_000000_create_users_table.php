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
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->boolean('gender')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->date('birthdate')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('login_count')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('user_statuses'); // admin
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('first_name');
            $table->index('last_name');
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
