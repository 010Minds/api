<?php
namespace Operation\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/*
	Sets the operation status
*/
class OperationStatus
{
	const PENDING = 1;
	const ACCEPTED = 2;
	const REJECTED = 3;

	public static function getStatus($status)
	{
		switch($status)
		{
			case 'pending':
				$status = self::PENDING;
				break;

			case 'accepted':
				$status = self::ACCEPTED;
				break;

			case 'rejected':
				$status = self::REJECTED;
				break;

			default:
				throw new \Exception("Status invalid in getStatus", 1);

		}

		$status = (int)$status;
		return $status;
	}
}

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

class Operation implements InputFilterAwareInterface
{

	public $id;
	public $userId;
	public $stockId;
	public $qtd;
	public $value;
	public $type;
	public $status;
	public $reason;
	public $createDate;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id 			= (!empty($data['id'])) ? $data['id'] : null;
		$this->userId 		= (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->stockId 		= (!empty($data['stock_id'])) ? $data['stock_id'] : null;
		$this->qtd 			= (!empty($data['qtd'])) ? $data['qtd'] : null;
		$this->value 		= (!empty($data['value'])) ? $data['value'] : null;
		$this->type 		= (!empty($data['type'])) ? $data['type'] : null;
		$this->status 		= (!empty($data['status'])) ? $data['status'] : null;
		$this->reason 		= (!empty($data['reason'])) ? $data['reason'] : null;
		$this->createDate 	= (!empty($data['create_date'])) ? $data['create_date'] : null;
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