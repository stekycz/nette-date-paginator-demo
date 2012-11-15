<?php

use steky\nette\DatePaginator\IModel;
use Nette\DateTime;

/**
 * Model pro testování DatePaginatoru.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-23
 */
class DummyModel implements IModel {

    /** @var null|array */
	private $data = null;

	/**
	 * Vytvoření testovací data.
	 */
	public function __construct() {
		if ($this->data === null) {
			$this->data = array();
			srand(47);
			for ($i = 0; $i < 100; $i++) {
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
		return new DateTime($oldest_date);
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
		return new DateTime($newest_date);
	}

	/**
	 * Vrací pole dat pro zobrazení v zadané datum.
	 *
	 * @param \DateTime $date
	 * @return array
	 */
	public function getForDate(\DateTime $date) {
		return array_filter($this->data, function ($row) use ($date) {
			return $row['date']->format('Y-m-d') === $date->format('Y-m-d');
		});
	}

    /**
     * Vrací datum nejbližšího staršího záznamu než zadané datum. Pokud
     * neexisttuje starší, vrací zadané.
     *
     * @param \DateTime $current_date
     * @return \DateTime
     */
    public function getClosestPrevious(\DateTime $current_date)
    {
	    $closest_previous = null;
	    foreach ($this->data as $row) {
		    if (($row['date'] < $current_date && $row['date'] > $closest_previous) || $closest_previous === null) {
			    $closest_previous = $row['date'];
			    break;
		    }
	    }
	    return new DateTime($closest_previous !== null ? $closest_previous : $this->getOldestDate());
    }

    /**
     * Vrací datum nejbližšího novějšího záznamu než zadané datum. Pokud
     * neexisttuje starší, vrací zadané.
     *
     * @param \DateTime $current_date
     * @return \DateTime
     */
    public function getClosestNext(\DateTime $current_date)
    {
	    $closest_next = null;
	    foreach ($this->data as $row) {
		    if (($row['date'] > $current_date && $row['date'] < $closest_next) || $closest_next === null) {
			    $closest_next = $row['date'];
			    break;
		    }
	    }
	    return new DateTime($closest_next !== null ? $closest_next : $this->getNewestDate());
    }

}
