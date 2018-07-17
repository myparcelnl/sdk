<?php
/**
 * Created by PhpStorm.
 * User: richardperdaan
 * Date: 20-04-18
 * Time: 14:09
 */

class GetMultipleConsignments extends \PHPUnit\Framework\TestCase {

	const NUMBER_OF_CONSIGNMENTS = 200;

	public function setUp() {
		if (getenv('API_KEY') == null) {
			echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
			return $this;
		}
	}

	public function testGetData() {
		$collection = new \MyParcelNL\Sdk\src\Helper\MyParcelCollection();
		$collection->setLatestDataWithoutIds( getenv('API_KEY'), self::NUMBER_OF_CONSIGNMENTS );

		$this->assertSame( count( $collection->getConsignments() ), self::NUMBER_OF_CONSIGNMENTS );
	}
}