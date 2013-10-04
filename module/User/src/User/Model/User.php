<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{

	public $id;
	public $mail;
	public $user;
	public $password;
	public $name;
	public $reais;
	public $dollars;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 		= (!empty($data['id'])) ? $data['id'] : null;
		$this->mail 	= (!empty($data['mail'])) ? $data['mail'] : null;
		$this->user 	= (!empty($data['user'])) ? $data['user'] : null;
		$this->password = (!empty($data['password'])) ? $data['password'] : null;
		$this->name 	= (!empty($data['name'])) ? $data['name'] : null;
		$this->reais 	= (!empty($data['reais'])) ? $data['reais'] : null;
		$this->dollars 	= (!empty($data['dollars'])) ? $data['dollars'] : null;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'		=> 'id',
				'required'	=> false,
				'filters'	=> array(
					array('name' => 'Int'),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'mail',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
					),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'user',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'password',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'		=> 'StringLength',
						'options'	=> array(
							'encoding'	=> 'UTF-8',
							'min'		=> 5,
							'max'		=> 45,
						),
					),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'name',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'reais',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$inputFilter->add(array(
				'name'		=> 'dollars',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}