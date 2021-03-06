<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_metrics', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('stock_code', 3);
			$table->decimal('last_trade', 8, 2)->nullable();
			$table->double('average_daily_volume')->nullable();
			$table->double('EBITDA')->nullable();
			$table->decimal('earnings_per_share_current', 8, 2)->nullable();
			$table->decimal('earnings_per_share_next_year', 8, 2)->nullable();
			$table->decimal('price_to_earnings', 8, 2)->nullable();
			$table->decimal('price_to_book', 8, 2)->nullable();
			$table->decimal('year_high', 8, 2)->nullable();
			$table->decimal('year_low', 8, 2)->nullable();
			$table->decimal('fifty_day_moving_average', 8, 2)->nullable();
			$table->decimal('two_hundred_day_moving_average', 8, 2)->nullable();
			$table->string('market_cap')->nullable();
			$table->decimal('dividend_yield', 8, 2)->nullable();
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
		Schema::drop('stock_metrics');
	}

}
