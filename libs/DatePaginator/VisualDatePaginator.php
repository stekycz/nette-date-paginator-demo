<?php

namespace steky\nette\DatePaginator;
use \Nette\Application\UI\Control;
use \Nette\Application\UI\Form;
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
	 * @return \Nette\Forms\Form
	 */
	public function createComponentDateForm() {
		$form = new Form();
		$form->getElementPrototype()->style = 'margin: 0;';

		$form->addDatePicker('paginatorDate')
			->addRule(Form::VALID, 'Entered date is not valid!')
			->addCondition(Form::FILLED)
				->addRule(Form::RANGE, 'Entered date is not within allowed range.', array($this->getPaginator()->getOldestDate(), $this->getPaginator()->getNewestDate()));

		$form['paginatorDate']->setDefaultValue($this->getPaginator()->getDate());

		$form->onSuccess[] = callback($this, 'formSubmitted');

		return $form;
	}

	/**
	 * @param \Nette\Forms\Form $form
	 */
	public function formSubmitted(Form $form) {
		$values = $form->getValues();

		$this->getPaginator()->setDate($values['paginatorDate']);
		$this->date = $values['paginatorDate']->format('Y-m-d');

		$this->redirect('this', array('date' => $this->date, ));
	}

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
		$this->getPaginator()->setDate(new DateTime($this->date ?: 'now'));
	}

}
