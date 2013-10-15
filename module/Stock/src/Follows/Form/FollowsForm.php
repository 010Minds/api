<?php
namespace Follows\Form;

use Zend\Form\Form;

class FollowsForm extends Form
{
	public function __construct($name=null)
	{
		// we want to ignore the name passed
		parent::__construct('follows');

		$this->add(array(
			'name' => 'following',
			'type' => 'Text',
			'options' => array(
				'label' => 'Following',
			),
		));
	}
}