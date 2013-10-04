<?php
namespace Stock\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Stock implements InputFilterAwareInterface
{

	public $id;
	public $name;
	public $sigla;
	public $current;
	public $open;
	public $high;
	public $low;
	public $percent;
	public $url;
	public $country;
	public $updatedDate;
	public $stockExchangeId;
	public $volume;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 				= (!empty($data['id'])) ? $data['id'] : null;
		$this->name 			= (!empty($data['name'])) ? $data['name'] : null;
		$this->sigla 			= (!empty($data['sigla'])) ? $data['sigla'] : null;
		$this->current 			= (!empty($data['current'])) ? $data['current'] : null;
		$this->open 			= (!empty($data['open'])) ? $data['open'] : null;
		$this->high 			= (!empty($data['high'])) ? $data['high'] : null;
		$this->low 				= (!empty($data['low'])) ? $data['low'] : null;
		$this->percent 			= (!empty($data['percent'])) ? $data['percent'] : null;
		$this->url 				= (!empty($data['url'])) ? $data['url'] : null;
		$this->country 			= (!empty($data['country'])) ? $data['country'] : null;
		$this->updatedDate 		= (!empty($data['updated_date'])) ? $data['updated_date'] : null;
		$this->stockExchangeId 	= (!empty($data['stock_exchange_id'])) ? $data['stock_exchange_id'] : null;
		$this->volume 			= (!empty($data['volume'])) ? $data['volume'] : null;
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