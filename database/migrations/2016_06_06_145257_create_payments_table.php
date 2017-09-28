<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone', 20)->nullable();
            $table->string('client_name', 300)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->double('amount', 8, 2)->nullable();
            $table->integer('status')->default(0);
            $table->string('account_no')->nullable();
            $table->string('transaction_time', 20)->nullable();
            $table->string('paybill')->default(963334);
            $table->string('comments', 700)->nullable();
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
        Schema::drop('payments');
    }
}
