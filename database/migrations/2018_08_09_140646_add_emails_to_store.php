<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailsToStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store', function (Blueprint $table) {
            $table->enum('reports', ['none','preset','custom'])->default('none');
            $table->text('custom_reports')->nullable();
            $table->text('to_emails');
            $table->text('bcc_emails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store', function (Blueprint $table) {
            $table->dropColumn(['reports', 'custom_reports', 'to_emails', 'bcc_emails']);
        });
    }
}
