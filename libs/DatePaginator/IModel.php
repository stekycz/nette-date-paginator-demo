<?php

namespace steky\nette\DatePaginator;
use \DateTime;

/**
 * Rozhraní pro práci s modelem. Umožňuje upravovat stránkování
 * podle dat v modelu.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-08-14
 */
interface IModel {

	/**
	 * Vrací datum nejstaršího záznamu.
	 *
	 * @abstract
	 * @return \DateTime
	 */
	public function getOldestDate();

	/**
	 * Vrací datum nejnovějšího záznamu.
	 *
	 * @abstract
	 * @return \DateTime
	 */
	public function getNewestDate();

	/**
	 * Vrací datum nejbližšího staršího záznamu než zadané datum. Pokud
	 * neexisttuje starší, vrací zadané.
	 *
	 * @abstract
	 * @param \DateTime $current_date
	 * @return \DateTime
	 */
	public function getClosestPrevious(DateTime $current_date);

	/**
	 * Vrací datum nejbližšího novějšího záznamu než zadané datum. Pokud
	 * neexisttuje starší, vrací zadané.
	 *
	 * @abstract
	 * @param \DateTime $current_date
	 * @return \DateTime
	 */
	public function getClosestNext(DateTime $current_date);

}
