<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShirtOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shirt_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->double('collar', 8, 2);
            $table->double('shirt_length', 8, 2);
            $table->double('sleeve_length', 8, 2);
            $table->double('shoulder', 8, 2);
            $table->double('chest', 8, 2);
            $table->double('tummy', 8, 2);
            $table->double('hips', 8, 2);
            $table->double('cuff', 8, 2);
            $table->timestamps();

            $table->index('order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shirt_options');
    }
}
