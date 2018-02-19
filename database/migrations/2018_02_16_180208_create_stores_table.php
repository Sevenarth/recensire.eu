<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable();
            $table->string("name");
            $table->string("company_registration_no")->nullable();
            $table->string("company_name");
            $table->string("VAT")->nullable();
            $table->string("country");
            $table->integer('seller_id')->unsigned();
            $table->foreign('seller_id')->references('id')->on('seller')->nullable();
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
        Schema::dropIfExists('store');
    }
}
