<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash_code')->index();
            $table->string('amazon_order_id')->nullable();
            $table->string('review_url')->nullable();
            $table->string('reference_url');
            $table->text('instructions')->nullable();
            $table->integer('tester_id')->unsigned();
            $table->foreign('tester_id')->references('id')->on('tester')->nullable();
            $table->mediumInteger('status')->default(0);
            $table->string('paypal_account')->nullable();
            $table->float('refunded_amount', 8, 2)->nullable();
            $table->timestamp('expires_on');
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
        Schema::dropIfExists('test_unit');
    }
}