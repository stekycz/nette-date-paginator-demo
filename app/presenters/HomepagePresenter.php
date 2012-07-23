<?php

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
	public function __construct(Nette\DI\Container $context, DummyModel $model) {
		parent::__construct($context);
		$this->model = $model;
	}

	/**
	 * @return \steky\nette\DatePaginator\VisualDatePaginator
	 */
	public function createComponentDatePaginator() {
		$date_paginator = new \steky\nette\DatePaginator\VisualDatePaginator();
		$date_paginator->getPaginator()->setOldestDate($this->model->getOldestDate());
		$date_paginator->getPaginator()->setNewestDate($this->model->getNewestDate());
		if (!$date_paginator->getPaginator()->getDate()) {
			$date_paginator->getPaginator()->setDate(new DateTime());
		}
		return $date_paginator;
	}

	public function renderDefault() {
		$this->template->data = $this->model->getForDate($this->getComponent('datePaginator')->getPaginator()->getDate());
	}

}
