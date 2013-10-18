<?php
namespace Notification\Model;

use Zend\Db\TableGateway\TableGateway;

class NotificationTable
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

	public function getNotification($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();


		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	public function saveNotification(Notification $notification)
	{
		$data = array(
			'user_id' 			=> $notification->userId,
			'type'				=> $notification->type,
			'description' 		=> $notification->description,
		);

		$id = (int) $notification->id;

		if($id == 0){

	        // get date
	        date_default_timezone_set('America/Sao_Paulo');
	        $dataAtual = date('Y/m/d H:i:s');
	        $data['date'] = $dataAtual;

			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		}else{
			# update
			throw new \Exception("Error Processing Request", 1);

		}

		return $id;

	}

	public function updateNotification($data, $where)
	{
		$this->tableGateway->update($data, $where);
	}


	public function deleteNotification($id, $idUser)
	{
		$this->tableGateway->delete(array('id'=>$id, 'user_id'=>$idUser));
	}
}