<?php
namespace Operation\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Operation implements InputFilterAwareInterface
{

	public $id;
	public $userId;
	public $stockID;
	public $qtd;
	public $value;
	public $type;
	public $action;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 		= (!empty($data['id'])) ? $data['id'] : null;
		$this->userID 	= (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->stockID 	= (!empty($data['stock_id'])) ? $data['stock_id'] : null;
		$this->qtd 		= (!empty($data['qtd'])) ? $data['qtd'] : null;
		$this->value 	= (!empty($data['value'])) ? $data['value'] : null;
		$this->type 	= (!empty($data['type'])) ? $data['type'] : null;
		$this->action 	= (!empty($data['action'])) ? $data['action'] : null;
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

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}