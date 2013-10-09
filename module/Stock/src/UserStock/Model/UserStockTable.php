<?php
namespace UserStock\Model;

use Zend\Db\TableGateway\TableGateway;

class UserStockTable
{
	protected $tableGateway;
	protected $select;

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
			'id' 		    => $userStock->id,
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

	/**
	 * Método que faz a consulta do stock do user
	 * @param int $id id do user_stock. 
	 * @param int $user_id id do user_id.
	 * @return void void()
	 */
	public function deleteUserStock($id,$user_id)
	{
		$this->tableGateway->delete(array(
			'user_id' => $user_id,
			'id'      => $id
		));
	}

	/**
	 * Método que faz a consulta do stock do user
	 * @param int $id id do user. No método getList da Classe UserStockRest ele pode ser nulo
	 * @return mixed[] array
	 */
	public function getStockUser($uid, $id = null){
		$id = (int) $id;
		$uid = (int) $uid;
		

		if(!empty($id)){
			$resultSet = $this->tableGateway->select(array(
				'user_id' => $uid ,
				'id'      => $id
			));
		}
		else{
			$resultSet = $this->tableGateway->select(array('user_id' => $uid));	
		}
		
		return $resultSet;
	}

	/**
	 * Método que faz a consulta do stock por id
	 * @param int $id id do stock.
	 * @return mixed[] array
	 */
	public function getStock($id){
		$id     = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row    = $rowset->current();

		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}
}