<?php

namespace DatePaginator\tests;
use \steky\nette\DatePaginator\DatePaginator;
use \PHPUnit_Framework_TestCase;

/**
 * Testy pro třídu stránkovače podle data.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-23
 */
class DatePaginator_Test extends PHPUnit_Framework_TestCase {

	public function testDefaultStep() {
		$date_paginator = new DatePaginator();
		$this->assertEquals(DatePaginator::DEFAULT_STEP, $date_paginator->getStep());
	}

}
