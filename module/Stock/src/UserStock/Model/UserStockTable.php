<?php
namespace UserStock\Model;

use Zend\Db\TableGateway\TableGateway;

class UserStockTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();

        return $resultSet;
	}

	public function getUserStock($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('user_id' => $id));
		// $row = $rowset->current();

		/*if(!$row){
			throw new \Exception("Could not find row $id");
		}*/

		return $rowset;
	}

	public function saveUserStock(UserStock $userStock)
	{
		$data = array(
			'stock_id' 		=> $userStock->stockId,
			'qtd' 			=> $userStock->qtd,
			'value' 		=> $userStock->value,
			'create_date' 	=> $userStock->createDate,
		);
/*
		$id = (int) $userStock->id;

		if($id == 0){
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if($this->getUserStock($id)){
				$this->tableGateway->update($data, array('user' => $id));
			} else {
				throw new \Exception("UserStock id does not exist");
			}
		}

		return $id;*/
	}

	public function deleteUserStock($id)
	{
		$this->tableGateway->delete(array('user_id'=>$id));
	}
}