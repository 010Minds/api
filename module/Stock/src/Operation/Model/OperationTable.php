<?php
namespace Operation\Model;

use Zend\Db\TableGateway\TableGateway;

class OperationTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

/*	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();

        return $resultSet;
	}*/

	public function getOperations($idUser, $option, $type)
	{
		$idUser = (int)$idUser;

		$where= array();
		if($type){
			$where['type'] = $type;
		}
		$where['user_id'] = $idUser;
		$where['action']  =$option;

		$resultSet = $this->tableGateway->select($where);


		return $resultSet;
	}

/*	public function getOperation($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();

		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	public function saveOperation(Operation $stock)
	{
		$data = array(
			'user_id' 			=> $stock->userId,
			'stock_id' 			=> $stock->stockID,
			'qtd' 				=> $stock->qtd,
			'value' 			=> $stock->value,
			'type'				=> $stock->type,
			'action' 			=> $stock->action,
		);

	}

	public function deleteStock($id)
	{
		$this->tableGateway->delete(array('id'=>$id));
	}*/
}