<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_order', function (Blueprint $table) {
            $table->increments('id');
            $table->float("fee", 8, 2)->default(0)->nullable();
            $table->text("description")->nullable();
            $table->integer("quantity");
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('set null')->nullable();
            $table->foreign('store_id')->references('id')->on('store')->onDelete('set null')->nullable();
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
        Schema::dropIfExists('test_order');
    }
}
