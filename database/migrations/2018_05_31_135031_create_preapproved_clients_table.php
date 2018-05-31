<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreapprovedClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preapproved_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mobile_number');
            $table->integer('national_id_number');
            $table->text('names');
            $table->integer('net_salary');
            $table->integer('loan_limits');
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
        Schema::dropIfExists('preapproved_clients');
    }
}
