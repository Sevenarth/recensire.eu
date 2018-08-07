<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestersAmazonProfilesStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tester', function (Blueprint $table) {
            $table->text('amazon_profiles_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tester', function (Blueprint $table) {
            $table->dropColumn('amazon_profiles_statuses');
        });
    }
}
