<?php

namespace DatePaginator\tests;
use \steky\nette\DatePaginator\DatePaginator;
use \PHPUnit_Framework_TestCase;
use \DateTime;

/**
 * Základní testy pro třídu stránkovače podle data.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-23
 */
class DatePaginator_Test extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider dataProviderSetStepBasic
	 * @param int $expected
	 * @param int $step
	 */
	public function testSetStepBasic($expected, $step) {
		$date_paginator = new DatePaginator();
		$date_paginator->setStep($step);
		$this->assertEquals($expected, $date_paginator->getStep());
	}

	public function dataProviderSetStepBasic() {
		return array(
			array(1, 1),
			array(2, 2),
			array(3, 3),
			array(599, 599),
			array(123, "123"),
		);
	}

	/**
	 * @dataProvider dataProviderSetStepErrors
	 * @expectedException \steky\nette\DatePaginator\InvalidArgumentException
	 * @param int $step
	 */
	public function testSetStepErrors($step) {
		$date_paginator = new DatePaginator();
		$date_paginator->setStep($step);
	}

	public function dataProviderSetStepErrors() {
		return array(
			array(0),
			array(-3),
			array(-599),
			array(123.345),
			array(-123.345),
			array(null),
			array("123.5"),
			array(""),
			array("0"),
			array("test"),
			array(10.00),
			array("10.00"),
		);
	}

	/**
	 * Otestuje, zda je výchozí krok opravdu výchozí.
	 *
	 * @depends testSetStepBasic
	 * @depends testSetStepErrors
	 */
	public function testDefaultStep() {
		$date_paginator = new DatePaginator();
		$this->assertEquals(DatePaginator::DEFAULT_STEP, $date_paginator->getStep());
	}

	/**
	 * @dataProvider dataProviderSetDate
	 * @param \DateTime $expected
	 * @param \DateTime $value
	 */
	public function testSetDate(DateTime $expected, DateTime $value) {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate($value);
		$this->assertEquals($expected, $date_paginator->getDate());
	}

	/**
	 * Testuje, že zadané datum nikdy nevystoupí ze zadaných mezí minimálního
	 * a maximálního data.
	 *
	 * @depends testSetDate
	 */
	public function testGetDateInRange() {
		$date_paginator = new DatePaginator();
		$date_paginator->setOldestDate(new DateTime('2012-07-14'));
		$date_paginator->setNewestDate(new DateTime('2012-08-05'));

		$date_paginator->setDate(new DateTime('2012-07-01'));
		$this->assertEquals($date_paginator->getOldestDate(), $date_paginator->getDate());


		$date_paginator->setDate(new DateTime('2012-08-31'));
		$this->assertEquals($date_paginator->getNewestDate(), $date_paginator->getDate());
	}

	/**
	 * @dataProvider dataProviderSetDate
	 * @param \DateTime $expected
	 * @param \DateTime $value
	 */
	public function testSetNewestDate(DateTime $expected, DateTime $value) {
		$date_paginator = new DatePaginator();
		$date_paginator->setNewestDate($value);
		$this->assertEquals($expected, $date_paginator->getNewestDate());
	}

	/**
	 * @dataProvider dataProviderSetDate
	 * @param \DateTime $expected
	 * @param \DateTime $value
	 */
	public function testSetOldestDate(DateTime $expected, DateTime $value) {
		$date_paginator = new DatePaginator();
		$date_paginator->setOldestDate($value);
		$this->assertEquals($expected, $date_paginator->getOldestDate());
	}

	public function dataProviderSetDate() {
		return array(
			array(new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-22 00:00:00')),
			array(new DateTime('2012-07-23 00:00:00'), new DateTime('2012-07-23 12:34:56')),
		);
	}

	/**
	 * @dataProvider dataProviderSetNewestOldestDateErrors
	 * @expectedException \steky\nette\DatePaginator\InvalidArgumentException
	 * @param \DateTime $newestDate
	 * @param \DateTime $oldestDate
	 */
	public function testSetNewestDateErrors(DateTime $newestDate, DateTime $oldestDate) {
		$date_paginator = new DatePaginator();
		$date_paginator->setOldestDate($oldestDate);
		$date_paginator->setNewestDate($newestDate);
	}

	/**
	 * @dataProvider dataProviderSetNewestOldestDateErrors
	 * @expectedException \steky\nette\DatePaginator\InvalidArgumentException
	 * @param \DateTime $newestDate
	 * @param \DateTime $oldestDate
	 */
	public function testSetOldestDateErrors(DateTime $newestDate, DateTime $oldestDate) {
		$date_paginator = new DatePaginator();
		$date_paginator->setNewestDate($newestDate);
		$date_paginator->setOldestDate($oldestDate);
	}

	public function dataProviderSetNewestOldestDateErrors() {
		return array(
			array(new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-23 00:00:00')),
			array(new DateTime('2012-07-23 00:00:00'), new DateTime('2012-07-24 12:34:56')),
		);
	}

	/**
	 * @expectedException \steky\nette\DatePaginator\InvalidArgumentException
	 */
	public function testSetOldestDateOlderCurrent() {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate(new DateTime('2012-07-23'));
		$date_paginator->setOldestDate(new DateTime('2012-07-24'));
	}

	/**
	 * @expectedException \steky\nette\DatePaginator\InvalidArgumentException
	 */
	public function testSetNewestDateNewerCurrent() {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate(new DateTime('2012-07-23'));
		$date_paginator->setNewestDate(new DateTime('2012-07-22'));
	}

	/**
	 * Testuje, zda není upravena instance zadaná jako parametr.
	 */
	public function testNotModifiedParameter() {
		$value = new DateTime('2012-07-23 12:34:56');
		$date_paginator = new DatePaginator();

		$date_paginator->setDate($value);
		$this->assertEquals($value, new DateTime('2012-07-23 12:34:56'));

		$date_paginator->setNewestDate($value);
		$this->assertEquals($value, new DateTime('2012-07-23 12:34:56'));

		$date_paginator->setOldestDate($value);
		$this->assertEquals($value, new DateTime('2012-07-23 12:34:56'));
	}

	/**
	 * @dataProvider dataProviderWrongParameterType
	 * @expectedException \Nette\FatalErrorException
	 * @param mixed $parameter
	 */
	public function testWrongParameterTypeSetDate($parameter) {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate($parameter);
	}

	/**
	 * @dataProvider dataProviderWrongParameterType
	 * @expectedException \Nette\FatalErrorException
	 * @param mixed $parameter
	 */
	public function testWrongParameterTypeSetOldestDate($parameter) {
		$date_paginator = new DatePaginator();
		$date_paginator->setOldestDate($parameter);
	}

	/**
	 * @dataProvider dataProviderWrongParameterType
	 * @expectedException \Nette\FatalErrorException
	 * @param mixed $parameter
	 */
	public function testWrongParameterTypeSetNewestDate($parameter) {
		$date_paginator = new DatePaginator();
		$date_paginator->setNewestDate($parameter);
	}

	public function dataProviderWrongParameterType() {
		return array(
			array("testovací řetězec"),
			array(null),
			array(123),
			array(1.23),
			array(''),
			array(0),
			array(0.0),
		);
	}

	/**
	 * @depends testSetDate
	 * @depends testSetOldestDate
	 * @dataProvider dataProviderIsOldest
	 * @param bool $expected
	 * @param \DateTime $current_date
	 * @param \DateTime $oldest_date
	 */
	public function testIsOldest($expected, DateTime $current_date, DateTime $oldest_date) {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate($current_date);
		$date_paginator->setOldestDate($oldest_date);
		$this->assertEquals($expected, $date_paginator->isOldest());
	}

	public function dataProviderIsOldest() {
		return array(
			array(true, new DateTime('2012-07-14 00:00:00'), new DateTime('2012-07-14 00:00:00')),
			array(false, new DateTime('2012-07-15 00:00:00'), new DateTime('2012-07-14 00:00:00')),
			array(true, new DateTime('2012-07-14 23:59:59'), new DateTime('2012-07-14 00:00:00')),
			array(false, new DateTime('2012-07-15 00:00:01'), new DateTime('2012-07-14 23:59:59')),
		);
	}

	/**
	 * @depends testSetDate
	 * @depends testSetNewestDate
	 * @dataProvider dataProviderIsNewest
	 * @param bool $expected
	 * @param \DateTime $current_date
	 * @param \DateTime $newest_date
	 */
	public function testIsNewest($expected, DateTime $current_date, DateTime $newest_date) {
		$date_paginator = new DatePaginator();
		$date_paginator->setDate($current_date);
		$date_paginator->setNewestDate($newest_date);
		$this->assertEquals($expected, $date_paginator->isNewest());
	}

	public function dataProviderIsNewest() {
		return array(
			array(true, new DateTime('2012-07-14 00:00:00'), new DateTime('2012-07-14 00:00:00')),
			array(false, new DateTime('2012-07-14 00:00:00'), new DateTime('2012-07-15 00:00:00')),
			array(true, new DateTime('2012-07-14 23:59:59'), new DateTime('2012-07-14 00:00:00')),
			array(false, new DateTime('2012-07-14 23:59:59'), new DateTime('2012-07-15 00:00:01')),
		);
	}

	/**
	 * @depends testSetOldestDate
	 * @depends testSetNewestDate
	 * @dataProvider dataProviderGetDays
	 * @param int $expected
	 * @param \DateTime $oldest_date
	 * @param \DateTime $newest_date
	 */
	public function testGetDays($expected, DateTime $oldest_date, DateTime $newest_date) {
		$date_paginator = new DatePaginator();
		$date_paginator->setOldestDate($oldest_date);
		$date_paginator->setNewestDate($newest_date);
		$this->assertEquals($expected, $date_paginator->getDays());
	}

	public function dataProviderGetDays() {
		return array(
			array(1, new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-23 00:00:00')),
			array(0, new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-22 00:00:00')),
			array(0, new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-22 23:59:59')),
			array(1, new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-23 00:00:01')),
			array(7, new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-29 12:00:00')),
		);
	}

	/**
	 * @depends testSetNewestDateErrors
	 * @depends testSetOldestDateErrors
	 * @depends testGetDays
	 * @dataProvider dataProviderSetNewestOldestDateErrors
	 * @expectedException \steky\nette\DatePaginator\InvalidStateException
	 * @param \DateTime $newestDate
	 * @param \DateTime $oldestDate
	 */
	public function testDaysErrors(DateTime $newestDate, DateTime $oldestDate) {
		$date_paginator = new DatePaginator();

		// Tady musíme použít hack přes reflexi kvůli kontrolám v setterech
		$property = $date_paginator->getReflection()->getProperty('newestDate');
		$property->setAccessible(true);
		$property->setValue($date_paginator, $newestDate);

		$property = $date_paginator->getReflection()->getProperty('oldestDate');
		$property->setAccessible(true);
		$property->setValue($date_paginator, $oldestDate);

		// Tady musíme dostat výjimku
		$date_paginator->getDays();
	}

	/**
	 * @dataProvider dataProviderGetPreviousDate
	 * @param \DateTime $expected
	 * @param \DateTime $current
	 * @param int $step
	 */
	public function testGetPreviousDate(DateTime $expected, DateTime $current, $step) {
		static $date_paginator = null;
		if ($date_paginator === null) {
			$date_paginator = new DatePaginator();
			$date_paginator->setOldestDate(new DateTime('2012-07-14'));
			$date_paginator->setNewestDate(new DateTime('2012-08-05'));
		}
		$date_paginator->setDate($current);
		$date_paginator->setStep($step);
		$this->assertEquals($expected, $date_paginator->getPreviousDate());
	}

	public function dataProviderGetPreviousDate() {
		return array(
			array(new DateTime('2012-07-22 00:00:00'), new DateTime('2012-07-23 00:00:00'), 1),
			array(new DateTime('2012-07-21 00:00:00'), new DateTime('2012-07-23 00:00:00'), 2),
			array(new DateTime('2012-07-18 00:00:00'), new DateTime('2012-07-23 00:00:00'), 5),
			array(new DateTime('2012-07-14 00:00:00'), new DateTime('2012-07-14 00:00:00'), 1),
		);
	}

	/**
	 * @dataProvider dataProviderGetNextDate
	 * @param \DateTime $expected
	 * @param \DateTime $current
	 * @param int $step
	 */
	public function testGetNextDate(DateTime $expected, DateTime $current, $step) {
		static $date_paginator = null;
		if ($date_paginator === null) {
			$date_paginator = new DatePaginator();
			$date_paginator->setOldestDate(new DateTime('2012-07-14'));
			$date_paginator->setNewestDate(new DateTime('2012-08-05'));
		}
		$date_paginator->setDate($current);
		$date_paginator->setStep($step);
		$this->assertEquals($expected, $date_paginator->getNextDate());
	}

	public function dataProviderGetNextDate() {
		return array(
			array(new DateTime('2012-07-24 00:00:00'), new DateTime('2012-07-23 00:00:00'), 1),
			array(new DateTime('2012-07-25 00:00:00'), new DateTime('2012-07-23 00:00:00'), 2),
			array(new DateTime('2012-07-28 00:00:00'), new DateTime('2012-07-23 00:00:00'), 5),
			array(new DateTime('2012-08-05 00:00:00'), new DateTime('2012-08-05 00:00:00'), 1),
		);
	}

}
