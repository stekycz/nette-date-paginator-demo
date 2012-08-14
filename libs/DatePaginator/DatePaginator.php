<?php

namespace steky\nette\DatePaginator;
use \Nette\Object;
use \DateTime;

/**
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-21
 *
 * @property \DateTime $date
 * @property-read bool $oldest
 * @property-read bool $newest
 * @property-read int $days
 * @property-read \DateTime $previousDate
 * @property-read \DateTime $nextDate
 */
class DatePaginator extends Object {

	/** @var \DateTime|null */
	private $date = null;

	/** @var IPeriod|null */
	private $period = null;

	/** @var IModel|null */
	private $model = null;

	/**
	 * Pokud zadáme datum mimo nastavené minimum a maximum,
	 * zarovná se autiomaticky na daný okraj.
	 *
	 * @param \DateTime $date
	 */
	public function setDate(DateTime $date) {
		$date = $this->period->normalizeDate($date);
		if ($this->getOldestDate() !== null && $this->getOldestDate() > $date) {
			$date = clone $this->getOldestDate();
		} elseif ($this->getNewestDate() !== null && $this->getNewestDate() < $date) {
			$date = clone $this->getNewestDate();
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
	 * @param IModel $model
	 * @return DatePaginator
	 */
	public function setModel(IModel $model) {
		$this->model = $model;
		return $this;
	}

	/**
	 * Nastaví stránkovačí periodu.
	 *
	 * @param IPeriod $period
	 * @return DatePaginator
	 */
	public function setPeriod(IPeriod $period) {
		$this->period = $period;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getOldestDate() {
		return clone $this->period->normalizeDate($this->model->getOldestDate());
	}

	/**
	 * @return \DateTime
	 */
	public function getNewestDate() {
		return clone $this->period->normalizeDate($this->model->getNewestDate());
	}

	/**
	 * @return bool
	 */
	public function isOldest() {
		return $this->getOldestDate() == $this->date;
	}

	/**
	 * @return bool
	 */
	public function isNewest() {
		return $this->getNewestDate() == $this->date;
	}

	/**
	 * Vrací datum předchozího dne. Pokud bychom měli vrátit starší datum než nejstarší,
	 * tak vrátíme právě to nejstarší.
	 *
	 * @return \DateTime
	 */
	public function getPreviousDate() {
		if ($this->date == $this->getOldestDate()) {
			return $this->date;
		}
		$previous_date = clone $this->date;
		$previous_date = $previous_date->modify('- ' . $this->period->getPeriod());
		$closest_previous = $this->period->normalizeDate($this->model->getClosestPrevious($this->date));
		if ($closest_previous < $previous_date) {
			$previous_date = clone $closest_previous;
		}
		return $previous_date;
	}

	/**
	 * Vrací datum následujícího dne. Pokud bychom měli vrátit novější datum než nejnovější,
	 * tak vrátíme právě to nejnovější.
	 *
	 * @return \DateTime
	 */
	public function getNextDate() {
		if ($this->date == $this->getNewestDate()) {
			return $this->date;
		}
		$next_date = clone $this->date;
		$next_date = $next_date->modify('+ ' . $this->period->getPeriod());
		$closest_next = $this->period->normalizeDate($this->model->getClosestNext($this->date));
		if ($closest_next > $next_date) {
			$next_date = clone $closest_next;
		}
		return $next_date;
	}

	/**
	 * Vrací počet dní mezi nejstarším a nejnovějším datem.
	 *
	 * @return int
	 * @throws InvalidStateException Pokud zjistíme, že neest date je starší než oldest
	 */
	public function getDays() {
		if ($this->getNewestDate() < $this->getOldestDate()) {
			throw new InvalidStateException('Newest date is older than Oldest date.');
		}
		$difference = $this->getNewestDate()->diff($this->getOldestDate());
		return (int) $difference->days;
	}

}
