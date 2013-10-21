<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
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

	public function getUser($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();


		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	public function saveUser(User $user)
	{
		if($user->public_profile == 'false'){
			$user->public_profile = 0;
		}else{
			$user->public_profile = 1;
		}

		$data = array(
			'mail' 		     => $user->mail,
			'user' 		     => $user->user,
			'password' 	     => $user->password,
			'name' 		     => $user->name,
			'reais'		     => $user->reais,
			'dollars' 	     => $user->dollars,
			'public_profile' => $user->public_profile,
		);

		$id      = (int) $user->id;
		$retorno = '';
		if($id == 0){ 
			$this->tableGateway->insert($data);
			$retorno = $this->tableGateway->getLastInsertValue();
		} else { 
			if($this->getUser($id)){ 
				$retorno = $this->tableGateway->update($data, array('id' => $id));
				
				if($retorno > 0){
					 $retorno = $id;
				}
			} else {
				throw new \Exception("User id does not exist");
			}
		}

		return $retorno;
	}

	public function updateUser($data, $where)
	{
		$this->tableGateway->update($data, $where);
	}

	public function deleteUser($id)
	{
		$return = $this->tableGateway->delete(array('id'=>$id));
		if(empty($return)){
			throw new \Exception("User id does not exist. Please provide a valid id");
		}
		return $return;
	}

}