<?php
namespace Notification\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


/*
	Sets the type status
*/
class TypeStatus
{
	const BUY  = 1;
	const SELL = 2;

	public static function getType($type)
	{
		switch($type)
		{
			case 'buy':
				$type = self::BUY;
				break;

			case 'sell':
				$type = self::SELL;
				break;

			default:
				throw new \Exception("Type invalid in getType", 1);

		}

		$type = (int)$type;
		return $type;
	}
}


class Notification implements InputFilterAwareInterface
{

	public $id;
	public $userId;
	public $type;
	public $description;
	public $date;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 			= (!empty($data['id'])) ? $data['id'] : null;
		$this->userId 		= (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->type 		= (!empty($data['type'])) ? $data['type'] : null;
		$this->description  = (!empty($data['description'])) ? $data['description'] : null;
		$this->date 		= (!empty($data['date'])) ? $data['date'] : null;
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