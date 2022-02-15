<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->string('name', 100);
            $table->string('extension', 100);
            $table->bigInteger('size');
            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type', 255);
            $table->timestamps();

            $table->index('imageable_id');
            $table->index('imageable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
