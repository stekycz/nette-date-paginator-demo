<?php

namespace steky\nette\DatePaginator;
use \Nette\Application\UI\Control;
use \DateTime;

/**
 * Visual paginator control.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-21
 */
class VisualDatePaginator extends Control {

	/** @persistent */
	public $date = null;

	/** @var DatePaginator */
	private $paginator = null;

	/**
	 * @return DatePaginator
	 */
	public function getPaginator() {
		if (!$this->paginator) {
			$this->paginator = new DatePaginator();
		}
		return $this->paginator;
	}

	/**
	 * Renders paginator.
	 *
	 * @return void
	 */
	public function render() {
		$paginator = $this->getPaginator();
		$this->template->paginator = $paginator;
		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}

	/**
	 * Loads state informations.
	 *
	 * @param  array
	 * @return void
	 */
	public function loadState(array $params) {
		parent::loadState($params);
		$this->getPaginator()->setDate(new DateTime($this->date));
	}

}
