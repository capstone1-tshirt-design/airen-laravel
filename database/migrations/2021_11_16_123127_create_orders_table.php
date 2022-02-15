<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id', 50);
            $table->primary('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users'); // customer
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('order_statuses');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
