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
			$table->integer('productId')->nullable();
            $table->integer('minPrincipal')->default(0);
            $table->integer('maxPrincipal')->default(0);
			$table->integer('minNumberOfRepayments')->nullable();
			$table->integer('maxNumberOfRepayments')->nullable();
            $table->string('product_name', 255)->nullable();
			$table->integer('loanTermFrequency')->nullable();
			$table->integer('loanTermFrequencyType')->nullable();
			$table->string('loanType')->nullable();
			$table->integer('numberOfRepayments')->nullable();
			$table->integer('repaymentEvery')->nullable();
			$table->integer('repaymentFrequencyType')->nullable();
			$table->integer('interestRatePerPeriod')->nullable();
			$table->integer('amortizationType')->nullable();
			$table->integer('interestType')->nullable();
			$table->integer('interestCalculationPeriodType')->nullable();
			$table->integer('transactionProcessingStrategyId')->nullable();
			$table->integer('maxOutstandingLoanBalance')->nullable();
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
