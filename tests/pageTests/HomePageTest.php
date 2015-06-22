<?php

class HomePageTest extends TestCase{
	public function testIndexPage(){
		$this->visit('/')
			->see('Code')->see('Name')->see('Sector');
	}

	public function testSectorFilter(){
		$this->visit('/')
			->select('All','stockSector')
			->press('Filter')
			->see('Code')->see('Name')->see('Sector');
	}
}