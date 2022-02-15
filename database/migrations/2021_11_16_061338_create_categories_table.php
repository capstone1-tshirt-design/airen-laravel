<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255);
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users'); // admin
            $table->unsignedBigInteger('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users'); // admin
            $table->unsignedBigInteger('deleted_by_id')->nullable();
            $table->foreign('deleted_by_id')->references('id')->on('users'); // admin
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('created_by_id');
            $table->index('updated_by_id');
            $table->index('deleted_by_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
