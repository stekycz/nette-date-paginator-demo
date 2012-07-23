<?php

namespace steky\nette\DatePaginator;
use \Nette\Object;
use \DateTime;

/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-21
 *
 * @property \DateTime $date
 * @property \DateTime $oldestDate
 * @property \DateTime $newestDate
 * @property int $step
 * @property-read bool $oldest
 * @property-read bool $newest
 * @property-read int $days
 * @property-read \DateTime $previousDate
 * @property-read \DateTime $nextDate
 */
class DatePaginator extends Object {

	/** Výchozí počet dní jednoho kroku stránky */
	const DEFAULT_STEP = 1;

	/** @var \DateTime */
	private $date;

	/** @var \DateTime */
	private $oldestDate;

	/** @var \DateTime */
	private $newestDate;

	/** @var int */
	private $step = self::DEFAULT_STEP;

	/**
	 * @param \DateTime $date
	 */
	public function setDate(DateTime $date) {
		$this->date = clone $date;
		$this->date = $this->date->setTime(0, 0, 0);
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $oldestDate
	 */
	public function setOldestDate($oldestDate) {
		$this->oldestDate = clone $oldestDate;
		$this->oldestDate = $this->oldestDate->setTime(0, 0, 0);
	}

	/**
	 * @return \DateTime
	 */
	public function getOldestDate() {
		return $this->oldestDate;
	}

	/**
	 * @param \DateTime $newestDate
	 */
	public function setNewestDate($newestDate) {
		$this->newestDate = clone $newestDate;
		$this->newestDate = $this->newestDate->setTime(0, 0, 0);
	}

	/**
	 * @return \DateTime
	 */
	public function getNewestDate() {
		return $this->newestDate;
	}

	/**
	 * @return bool
	 */
	public function isOldest() {
		return $this->oldestDate == $this->date;
	}

	/**
	 * @return bool
	 */
	public function isNewest() {
		return $this->newestDate == $this->date;
	}

	/**
	 * @return int
	 */
	public function getStep() {
		return $this->step;
	}

	/**
	 * Nastaví krok pro posun ve dnech.
	 *
	 * @param int $step
	 * @throws InvalidArgumentException Pokud je krok menší jak 1.
	 */
	public function setStep($step) {
		if ($step < 1) {
			throw new InvalidArgumentException('Step for DatePaginator cannot be zero or negative.');
		}
		$this->step = $step;
	}

	/**
	 * Vrací počet dní mezi nejstarším a nejnovějším datem.
	 *
	 * @return int
	 */
	public function getDays() {
		$difference = $this->newestDate->diff($this->oldestDate);
		return $difference->days;
	}

	/**
	 * @return \DateTime
	 */
	public function getPreviousDate() {
		$previous_date = clone $this->date;
		$previous_date = $previous_date->modify('- ' . $this->step . ' days');
		return $previous_date;
	}

	/**
	 * @return \DateTime
	 */
	public function getNextDate() {
		$next_date = clone $this->date;
		$next_date = $next_date->modify('+ ' . $this->step . ' days');
		return $next_date;
	}

}
