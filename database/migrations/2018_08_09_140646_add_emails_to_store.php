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
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject');
            $table->text('preface')->nullable();
            $table->text('queries');
            $table->text('postface')->nullable();
            $table->timestamps();
        });

        Schema::table('store', function (Blueprint $table) {
            $table->unsignedInteger('report_id')->nullable();
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('set null')->nullable();
            $table->text('to_emails')->nullable();
            $table->text('bcc_emails')->nullable();
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
            $table->dropForeign('store_report_id_foreign');
            $table->dropColumn(['report_id', 'to_emails', 'bcc_emails']);
        });
        Schema::dropIfExists('reports');
    }
}
