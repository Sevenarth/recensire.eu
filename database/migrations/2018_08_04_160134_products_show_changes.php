<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductsShowChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("test_order", function (Blueprint $table) {
            $table->boolean("is_product_link_public")->default(false);
            $table->boolean("is_product_public")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("test_order", function (Blueprint $table) {
            $table->dropColumn("is_product_link_public");
            $table->dropColumn("is_product_public");
        });
    }
}
