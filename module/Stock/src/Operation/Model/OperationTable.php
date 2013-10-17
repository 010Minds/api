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

	public function getOperations($idUser, $status, $type)
	{
		$idUser = (int)$idUser;
		$status = $status;
		$type 	= $type;

		$where= array();
		if($status){
			$status = OperationStatus::getStatus($status);
			$where['status'] = $status;
		}
		if($type){
			$type = TypeStatus::getType($type);
			$where['type'] = $type;
		}
		$where['user_id'] = $idUser;

		$resultSet = $this->tableGateway->select($where);

		return $resultSet;
	}

	/*
		Busca todas as operações filtrando pelo seu status
	*/
	public function getOperationsStatus($status)
	{
		$status = OperationStatus::getStatus($status);
		$where['status'] = $status;
		$resultSet = $this->tableGateway->select($where);

		return $resultSet;
	}

	public function get($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();

		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	public function saveOperation(Operation $operation)
	{
		$data = array(
			'user_id' 			=> $operation->userId,
			'stock_id'			=> $operation->stockId,
			'qtd' 				=> $operation->qtd,
			'value' 			=> $operation->value,
			'type'				=> $operation->type,
		);


		$id = (int) $operation->id;

		if($id == 0){

	        // get date
	        date_default_timezone_set('America/Sao_Paulo');
	        $dataAtual = date('Y/m/d H:i:s');
	        $data['create_date'] = $dataAtual;
	        // status default in save
	        $data['status'] = OperationStatus::PENDING;

	        $data['type'] = TypeStatus::getType($data['type']);

			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		}else{
			# update
			throw new \Exception("Error Processing Request", 1);

		}

		return $id;

	}

	public function updateOperation($data, $where)
	{
		$this->tableGateway->update($data, $where);
	}


	public function deleteOperation($id, $idUser)
	{
		$this->tableGateway->delete(array('id'=>$id, 'user_id'=>$idUser));
	}
}