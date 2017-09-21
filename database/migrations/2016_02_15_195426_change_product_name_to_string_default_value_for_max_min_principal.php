<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductNameToStringDefaultValueForMaxMinPrincipal extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('product_name', 255)->change();
            $table->integer('minPrincipal')->default(0)->change();
            $table->integer('maxPrincipal')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('product_name')->change();
            $table->integer('minPrincipal')->default(0)->change();
            $table->integer('maxPrincipal')->default(0)->change();
        });
    }

}
