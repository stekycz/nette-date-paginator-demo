<?php

namespace steky\nette\DatePaginator;
use \DateTime;

/**
 * Rozhraní definuje časovou periodu pro DatePaginator.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-08-14
 */
interface IPeriod {

	/**
	 * Vrací název periody.
	 *
	 * @abstract
	 * @return string
	 */
	public function getName();

	/**
	 * Vrací velikost posunu bez znaménka ve formátu, který akceptuje funkce strtotime().
	 *
	 * @abstract
	 * @return string
	 */
	public function getPeriod();

	/**
	 * Provede normalizaci data pro danou periodu. Jedná-li se o den zaokrouhlí na půlnoc daného data.
	 * Pro hodinovou periodu na začátek hodiny, tedy např.: 13:00:00.
	 *
	 * @abstract
	 * @param \DateTime $date
	 * @return \DateTime
	 */
	public function normalizeDate(DateTime $date);

}
