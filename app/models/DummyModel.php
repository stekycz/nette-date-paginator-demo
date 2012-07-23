<?php

/**
 * Model pro testování DatePaginatoru.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-23
 */
class DummyModel {

	/** @var null|array */
	private $data = null;

	/**
	 * Vytvoření testovací data.
	 */
	public function __construct() {
		if ($this->data === null) {
			$this->data = array();
			srand(47);
			for ($i = 0; $i < 10; $i++) {
				$day_move = rand(-14, +14);
				$date = new DateTime();
				$this->data[] = array(
					'name' => 'Test ' . $i,
					'date' => $date->modify($day_move . ' days'),
				);
			}
		}
	}

	/**
	 * Vrací nejstarší datum v data setu. Pokud jsou data prázdná, vrací NULL.
	 *
	 * @return DateTime|null
	 */
	public function getOldestDate() {
		$oldest_date = null;
		foreach ($this->data as $row) {
			if ($row['date'] < $oldest_date || $oldest_date === null) {
				$oldest_date = $row['date'];
			}
		}
		return $oldest_date;
	}

	/**
	 * Vrací nejnovější datum v data setu. Pokud jsou data prázdná, vrací NULL.
	 *
	 * @return DateTime|null
	 */
	public function getNewestDate() {
		$newest_date = null;
		foreach ($this->data as $row) {
			if ($row['date'] > $newest_date || $newest_date === null) {
				$newest_date = $row['date'];
			}
		}
		return $newest_date;
	}

	/**
	 * Vrací pole dat pro zobrazení v zadané datum.
	 *
	 * @param \DateTime $date
	 * @return array
	 */
	public function getForDate(DateTime $date) {
		return array_filter($this->data, function ($row) use ($date) {
			return $row['date']->format('Y-m-d') === $date->format('Y-m-d');
		});
	}

}
