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
            $table->string('hash_code')->unique()->index();
            $table->string('amazon_order_id')->nullable();
            $table->string('review_url')->nullable();
            $table->string('reference_url');
            $table->text('instructions');
            $table->integer('test_order_id')->unsigned();
            $table->foreign('test_order_id')->references('id')->on('test_order');
            $table->integer('tester_id')->unsigned();
            $table->foreign('tester_id')->references('id')->on('tester')->nullable();
            $table->mediumInteger('status')->default(0);
            $table->string('paypal_account')->nullable();
            $table->float('refunded_amount', 8, 2)->nullable();
            $table->int('refunding_type');
            $table->boolean('link_opened')->default(false);
            $table->boolean('refunded')->default(false);
            $table->int('expires_on_time');
            $table->int('expires_on_space');
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
