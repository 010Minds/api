<?php
namespace Exchange\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Exchange implements InputFilterAwareInterface{

	public $id;
	public $name;
	protected $inputFilter;

	public function exchangeArray($data){
		$this->id 	 = (!empty($data['id'])) ? $data['id'] : null;
		$this->name  = (!empty($data['name'])) ? $data['name'] : null;
	}

	public function getArrayCopy(){
		$_this = get_object_vars($this);
		unset($_this['inputFilter']);
		return $_this;
	}

	public function setInputFilter(InputFilterInterface $inputFilter){
		throw new \Exception("Not used");
	}

	public function getInputFilter(){
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'		=> 'id',
				'required'	=> false,
				'filters'	=> array(
					array('name' => 'Int'),
				),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}