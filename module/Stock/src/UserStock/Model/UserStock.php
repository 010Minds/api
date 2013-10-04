<?php
namespace UserStock\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UserStock implements InputFilterAwareInterface
{

	public $userId;
	public $stockId;
	public $qtd;
	public $value;
	public $createDate;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->userId		= (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->stockId 		= (!empty($data['stock_id'])) ? $data['stock_id'] : null;
		$this->qtd 			= (!empty($data['qtd'])) ? $data['qtd'] : null;
		$this->value 		= (!empty($data['value'])) ? $data['value'] : null;
		$this->createDate	= (!empty($data['create_date'])) ? $data['create_date'] : null;
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
				'name'		=> 'user_id',
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