<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 100);
            $table->string('description', 255);
            $table->double('price', 8, 2);
            $table->boolean('sale')->default(false);
            $table->double('old_price', 8, 2)->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users'); // admin
            $table->unsignedBigInteger('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users'); // admin
            $table->unsignedBigInteger('deleted_by_id')->nullable();
            $table->foreign('deleted_by_id')->references('id')->on('users'); // admin
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('code');
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
        Schema::dropIfExists('products');
    }
}
