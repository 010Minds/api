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
		if($option){
			$where['action'] = $option;
		}
		if($type){
			$where['type'] = $type;
		}
		$where['user_id'] = $idUser;

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

	        // add date
	        date_default_timezone_set('America/Sao_Paulo');
	        $dataAtual = date('Y/m/d H:i:s');
	        $data['create_date'] = $dataAtual;

	        // action default in save
	        $data['action'] = 'pending';

			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		}else{
			# update
			throw new \Exception("Error Processing Request", 1);

		}

		return $id;

	}

	public function updateOperationPending(){

		// Busca todas as operações pendentes
		// Table 'operation'
		$where['action'] = 'pending';
		$resultSet = $this->tableGateway->select($where);

        $data = array();
        foreach ($resultSet as $result) {
            $data[] = $result;
        }

        // Busca Saldo do usuário em reais e dollars
        // Table 'user'


        // Busca o valor atual e volume de cada stock
        // Table 'stock'

        // Busca stocks do usuario
        // dados necessários: id, current, volume






		print_r($data);

		exit();
	}


	public function deleteOperation($id, $idUser)
	{
		$this->tableGateway->delete(array('id'=>$id, 'user_id'=>$idUser));
	}
}