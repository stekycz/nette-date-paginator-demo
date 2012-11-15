<?php

use steky\nette\DatePaginator\VisualDatePaginator;
use steky\nette\DatePaginator\Period\Day;
use Nette\DI\Container;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

	/** @var \DummyModel */
	protected $model;

	/**
	 * @param \Nette\DI\Container $context
	 * @param DummyModel $model
	 */
	public function __construct(Container $context, DummyModel $model) {
		parent::__construct($context);
		$this->model = $model;
	}

	/**
	 * @return \steky\nette\DatePaginator\VisualDatePaginator
	 */
	public function createComponentDatePaginator() {
		$date_paginator = new VisualDatePaginator();
		$date_paginator->getPaginator()->setModel($this->model);
		$date_paginator->getPaginator()->setPeriod(new Day());
		return $date_paginator;
	}

	public function renderDefault() {
		$this->template->data = $this->model->getForDate($this->getComponent('datePaginator')->getPaginator()->getDate());
	}

}
