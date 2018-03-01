<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestUnitStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_unit_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_unit_id')->unsigned();
            $table->foreign('test_unit_id')->references('id')->on('test_unit')->onDelete('cascade');
            $table->mediumInteger('status');
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
        Schema::dropIfExists('test_unit_status');
    }
}
