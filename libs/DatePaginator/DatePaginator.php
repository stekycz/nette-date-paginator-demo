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
	private $date = null;

	/** @var \DateTime */
	private $oldestDate = null;

	/** @var \DateTime */
	private $newestDate = null;

	/** @var int */
	private $step = self::DEFAULT_STEP;

	/**
	 * Pokud zadáme datum mimo nastavené minimum a maximum,
	 * zarovná se autiomaticky na daný okraj.
	 *
	 * @param \DateTime $date
	 */
	public function setDate(DateTime $date) {
		$date = $this->normalizeDate($date);
		if ($this->oldestDate !== null && $this->oldestDate > $date) {
			$date = clone $this->oldestDate;
		} elseif ($this->newestDate !== null && $this->newestDate < $date) {
			$date = clone $this->newestDate;
		}
		$this->date = $date;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return clone $this->date;
	}

	/**
	 * @param \DateTime $oldestDate
	 * @throws InvalidArgumentException Pokud nastavuji datum novější než newest
	 */
	public function setOldestDate($oldestDate) {
		$oldest_date = $this->normalizeDate($oldestDate);
		if ($this->newestDate !== null && $this->newestDate < $oldest_date) {
			throw new InvalidArgumentException('Oldest date cannot be newer than newest.');
		} elseif ($this->date !== null && $oldest_date > $this->date) {
			throw new InvalidArgumentException('Given oldest date in newer than current date.');
		}
		$this->oldestDate = $oldest_date;
	}

	/**
	 * @return \DateTime
	 */
	public function getOldestDate() {
		return clone $this->oldestDate;
	}

	/**
	 * @param \DateTime $newestDate
	 * @throws InvalidArgumentException Pokud nastavuji datum starší než oldest
	 */
	public function setNewestDate($newestDate) {
		$newest_date = $this->normalizeDate($newestDate);
		if ($this->oldestDate !== null && $this->oldestDate > $newest_date) {
			throw new InvalidArgumentException('Newest date cannot be older than oldest.');
		} elseif ($this->date !== null && $newest_date < $this->date) {
			throw new InvalidArgumentException('Given newest date in older than current date.');
		}
		$this->newestDate = $newest_date;
	}

	/**
	 * @return \DateTime
	 */
	public function getNewestDate() {
		return clone $this->newestDate;
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
		if (!is_numeric($step)) {
			throw new InvalidArgumentException('Step for DatePaginator must be a number.');
		}
		// Tohle je nutné, protože se správně převede číselný řetězec na číslo (int nebo float)
		$step = 0 + $step;
		if (is_float($step)) {
			throw new InvalidArgumentException('Step for DatePaginator cannot be float.');
		}
		if ($step < 1) {
			throw new InvalidArgumentException('Step for DatePaginator cannot be zero or negative.');
		}
		$this->step = intval($step);
	}

	/**
	 * Vrací počet dní mezi nejstarším a nejnovějším datem.
	 *
	 * @return int
	 * @throws InvalidStateException Pokud zjistíme, že neest date je starší než oldest
	 */
	public function getDays() {
		if ($this->newestDate < $this->oldestDate) {
			throw new InvalidStateException('Newest date is older than Oldest date.');
		}
		$difference = $this->newestDate->diff($this->oldestDate);
		return $difference->days;
	}

	/**
	 * Vrací datum předchozího dne. Pokud bychom měli vrátit starší datum než nejstarší,
	 * tak vrátíme právě to nejstarší.
	 *
	 * @return \DateTime
	 */
	public function getPreviousDate() {
		if ($this->date == $this->oldestDate) {
			return $this->date;
		}
		$previous_date = clone $this->date;
		$previous_date = $previous_date->modify('- ' . $this->step . ' days');
		return $previous_date;
	}

	/**
	 * Vrací datum následujícího dne. Pokud bychom měli vrátit novější datum než nejnovější,
	 * tak vrátíme právě to nejnovější.
	 *
	 * @return \DateTime
	 */
	public function getNextDate() {
		if ($this->date == $this->newestDate) {
			return $this->date;
		}
		$next_date = clone $this->date;
		$next_date = $next_date->modify('+ ' . $this->step . ' days');
		return $next_date;
	}

	/**
	 * Upraví zadané datum tak, že složka hodin nebude mít vliv. Tedy nastaví
	 * čas na 00:00:00. Zadaný argument ale není  ovlivněn.
	 *
	 * @param \DateTime $date
	 * @return \DateTime
	 */
	private function normalizeDate(DateTime $date) {
		$clone = clone $date;
		return $clone->setTime(0, 0, 0);
	}

}
