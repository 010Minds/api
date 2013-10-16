<?php
namespace Timeline\Form;

use Zend\Form\Form;

class TimelineForm extends Form
{
	public function __construct($name=null)
	{
		// we want to ignore the name passed
		parent::__construct('follows');

		$this->add(array(
			'name' => 'description',
			'type' => 'Text',
			'options' => array(
				'label' => 'Description',
			),
		));
	}
}