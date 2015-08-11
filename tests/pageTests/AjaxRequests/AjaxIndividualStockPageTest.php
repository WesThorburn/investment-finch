<?php

class AjaxIndividualStockPageTest extends TestCase{
	public function testRelatedStocks(){
		$this->visit('/relatedstocks/CBA')
			->see('Related Stocks')
			->see('ANZ')
			->see('Australia And New Zealand Banking Group Limited')
			->see('WBC')
			->see('Westpac Banking Corporation');
	}

	public function testStockGraph(){
		$this->visit('/graph/CBA/last_month/Price')
			->see('Date')
			->see('Price')
			->see(date('M'))
			->see(date('Y'));

		$this->visit('/graph/CBA/all_time/Price')
			->see('Date')
			->see('Price')
			->see('Feb 1, 2000')
			->see(date('M'))
			->see(date('Y'));
	}
}