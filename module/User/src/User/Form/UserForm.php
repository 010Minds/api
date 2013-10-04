<?php
namespace User\Form;

use Zend\Form\Form;

class UserForm extends Form
{
	public function __construct($name=null)
	{
		// we want to ignore the name passed
		parent::__construct('user');

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'name',
			'type' => 'Text',
			'options' => array(
				'label' => 'Nome',
			),
		));

		$this->add(array(
			'name' => 'mail',
			'type' => 'Text',
			'options' => array(
				'label' => 'E-mail',
			),
		));

		$this->add(array(
			'name' => 'reais',
			'type' => 'Text',
			'options' => array(
				'label' => 'Reais',
			),
		));

		$this->add(array(
			'name' => 'dollars',
			'type' => 'Text',
			'options' => array(
				'label' => 'Dólares',
			),
		));

		$this->add(array(
			'name' => 'user',
			'type' => 'Text',
			'options' => array(
				'label' => 'Usuário',
			),
		));

		$this->add(array(
			'name' => 'password',
			'type' => 'Text',
			'options' => array(
				'label' => 'Senha',
			),
		));

		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'atributes' => array(
				'value' => 'Enviar',
				'id' => 'submitbutton',
			),
		));
	}
}