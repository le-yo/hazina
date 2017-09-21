<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionProgressPinToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->integer('session')->after('phone_no')->default(0);
			$table->integer('progress')->after('session')->default(0);
			$table->integer('pin')->after('progress')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('session');
			$table->dropColumn('progress');
			$table->dropColumn('pin');
		});
	}

}
