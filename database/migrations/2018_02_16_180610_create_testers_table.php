<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tester', function (Blueprint $table) {
            $table->increments('id');
            $table->json('amazon_profiles');
            $table->json('facebook_profiles');
            $table->string("name");
            $table->string("email");
            $table->string("wechat");
            $table->string("profile_image")->nullable();
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
        Schema::dropIfExists('tester');
    }
}
