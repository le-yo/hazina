<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUssdLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ussd_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('phone')->nullable();
			$table->string('text')->nullable();
			$table->string('session_id')->nullable();
			$table->string('service_code')->nullable();
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
		Schema::drop('ussd_logs');
	}

}
