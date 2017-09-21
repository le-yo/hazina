<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('productId');
            $table->integer('minPrincipal')->default(0);
            $table->integer('maxPrincipal')->default(0);
			$table->integer('minNumberOfRepayments');
			$table->integer('maxNumberOfRepayments');
            $table->string('product_name', 255)->nullable();
			$table->integer('loanTermFrequency');
			$table->integer('loanTermFrequencyType');
			$table->string('loanType');
			$table->integer('numberOfRepayments');
			$table->integer('repaymentEvery');
			$table->integer('repaymentFrequencyType');
			$table->integer('interestRatePerPeriod');
			$table->integer('amortizationType');
			$table->integer('interestType');
			$table->integer('interestCalculationPeriodType');
			$table->integer('transactionProcessingStrategyId');
			$table->integer('maxOutstandingLoanBalance');
			$table->integer('is_active')->default(0);
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
		Schema::drop('settings');
	}

}
