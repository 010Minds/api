<?php
namespace Operation\Form;

use Zend\Form\Form;

class OperationForm extends Form
{
	public function __construct($name=null)
	{
		// we want to ignore the name passed
		parent::__construct('operation');

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'user_id',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'stock_id',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'qtd',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'value',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'type',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'status',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'reason',
			'type' => 'Hidden',
		));
	}
}