<?php
namespace User\Model;

use Zend\I18n\Validator\Float;
use Zend\Validator\NotEmpty;
use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\InputFilter\Factory;

class User implements InputFilterAwareInterface
{

	public $id;
	public $mail;
	public $user;
	public $password;
	public $name;
	public $reais;
	public $dollars;
	public $public_profile;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 				= (!empty($data['id'])) ? $data['id'] : null;
		$this->mail 			= (!empty($data['mail'])) ? $data['mail'] : null;
		$this->user 			= (!empty($data['user'])) ? $data['user'] : null;
		$this->password 		= (!empty($data['password'])) ? $data['password'] : null;
		$this->name 			= (!empty($data['name'])) ? $data['name'] : null;
		$this->reais 			= (!empty($data['reais'])) ? $data['reais'] : null;
		$this->dollars 	        = (!empty($data['dollars'])) ? $data['dollars'] : null;
		$this->public_profile 	= (!empty($data['public_profile'])) ? $data['public_profile'] : 0;
	}

	public function getArrayCopy()
	{
		$_this = get_object_vars($this);
		unset($_this['inputFilter']);
		return $_this;
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new Factory();

			$inputFilter->add($factory->createInput(
				array(
					'name'		=> 'id',
					'required'	=> false,
					'filters'	=> array(
						array('name' => 'Int'),
					)
				)
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
						'name'    => 'EmailAddress',
						'options' => array(
							'messages' => array(
								EmailAddress::INVALID        => 'Please fill correctly the email field',
								EmailAddress::INVALID_FORMAT => 'Please fill correctly the email field'
							),
						),
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
					array(
						'name' => 'NumberFormat'
					),
				),
				'validators' => array(
		            array(
						'name'    => 'Float',
						'options' => array(
							'messages' => array(
								Float::INVALID   => 'Invalid',
								Float::NOT_FLOAT => 'NOT_FLOAT',
							),
						),
					),
		        ),
			));

			$inputFilter->add(array(
				'name'		=> 'dollars',
				'required'	=> true,
				'filters'	=> array(
					array(
						'name' => 'NumberFormat'
					),
				),
				'validators' => array(
		            array(
						'name'    => 'Float',
						'options' => array(
							'messages' => array(
								Float::INVALID   => 'Invalid',
								Float::NOT_FLOAT => 'NOT_FLOAT',
							),
						),
					),
		        ),
			));

			$inputFilter->add($factory->createInput(
				array(
					'name'		=> 'public_profile',
					'required'	=> false,
					'filters'	=> array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
					),		
				)
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}