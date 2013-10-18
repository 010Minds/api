<?php
namespace Follows\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Class FollowsTable Model
 * @api
 * @author Alexsandro Andre <andre@010minds.com>
 */
class FollowsTable{
	/**
     * propriedade que recebe o objeto
     */
	protected $tableGateway;

	/**
     * Construtor
     */
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}

	/**
     * Metodo que lista todos os follows
     * @return array $resultSet
     */
	public function fetchAll(){
		$resultSet = $this->tableGateway->select();

        return $resultSet;
	}

	/**
     * Metodo que lista um determinado Following
     * @param int $id do user que está querendo saber quem está seguindo
     * @return array $resultSet
     */ 
	public function getFollowing($id){
		$id     = (int) $id;

		if($id == 0){
			throw new \Exception("The user id can not be zero");
		}

		$resultSet = $this->tableGateway->select(array('user_id' => $id));

		if(empty($resultSet)){ 
			throw new \Exception("Could not find following");
		}

		return $resultSet;
	}

	/**
     * Metodo que lista um determinado follower
     * @param int $id do user que está querendo saber os seus seguidores
     * @return array $resultSet
     */ 
	public function getFollowers($id){
		$id     = (int) $id;
		$resultSet = $this->tableGateway->select(array('following' => $id));

		if(empty($resultSet)){ 
			throw new \Exception("Could not find followers");
		}

		return $resultSet;
	}

	public function myFollow($id,$userId){
		$id        = (int) $id;
		$resultSet = $this->tableGateway->select(array('following' => $id, 'user_id' => $userId));
		$row = $resultSet->current();
		
		if(!$row){
			$row = false;
		}
		
		return $row;
	}

	/**
     * Metodo que retorna a quantidade de followers
     * @param int $id do user que está querendo saber os seus seguidores
     * @return int $resultSet
     */ 
	public function fullFollowers($id){
		$id = (int) $id;
		$resultSet = $this->tableGateway->select(array('following' =>  $id));
		

		return (int) count($resultSet);
	}

	/**
     * Metodo que retorna a quantidade de Following
     * @param int $id do user que está querendo saber a quantidade de seguintes
     * @return int $resultSet
     */ 
	public function fullFollowing($id){
		$id = (int) $id;
		$resultSet = $this->tableGateway->select(array('user_id' =>  $id));
		

		return (int) count($resultSet);
	}

	/**
	 * Método que retorna o objeto follow (linha)
	 * @param  int $id id da linha do db
     * @return array $row
     */
	public function getObjFollow($id){
		$id        = (int) $id;
		$resultSet = $this->tableGateway->select(array('id' => $id));
		$row = $resultSet->current();
		
		if(!$row){
			throw new \Exception("Could not find row $id");
		}
		
		return $row;
	}

	public function getPending($id){
		$id = (int) $id;
		$resultSet = $this->tableGateway->select(array(
			'following'    =>  $id,
			'permission' => 0,
		));

		return $resultSet;	
	}

	/**
     * Metodo generico para listar os  Following
     * @param array array('user_id' => 1) or array('user_id' => 1,'permission' => false)
     * @return array $resultSet
     */ 
	public function customFollowing($arrayParam){

		if(empty($arrayParam)){
			throw new \Exception("The array can not be empty");
		}

		$resultSet = $this->tableGateway->select($arrayParam);

		if(empty($resultSet)){ 
			throw new \Exception("Could not find following");
		}

		return $resultSet;
	}

	/**
	 * Responsável por cadastrar a listas de follows
	 * @param objeto Follows
	 * @return int $id
	 */
	public function follow(Follows $follows){
		$data = array(
			'user_id' 	=> $follows->user_id,
			'following'	=> $follows->id,
		);
		
		$id = (int) $follows->id;
		
		$this->tableGateway->insert($data);
		$id = $this->tableGateway->getLastInsertValue();

		return $id;
	}

	/**
	 * Método resposável por aceitar seguidor
	 * @param obj follows
	 */
	public function acceptedFollow(Follows $follows)
	{
		$data = array(
			'permission' => $follows->permission ? 1 : 0,
		);

		$id = (int) $follows->id; 

		return $this->tableGateway->update($data, array('id' => $id));
	}

	/**
	 * Método resposável por não aceitar o seguidor
	 * @param int id id da tabela
	 */
	public function notAccpetedFollow($id)
	{
		$return = $this->tableGateway->delete(array(
			'id' => $id,
		));
		if(empty($return)){
			throw new \Exception("User id does not exist. Please provide a valid id");
		}
		return $return;
	}

	/**
	 * Método resposável por executar unfollow (deixar de seguir)
	 * @param int $user_id id do usuário
	 * @param int $id id do following a ser deletado
	 */
	public function deleteFollow($user_id,$id){
		$return = $this->tableGateway->delete(array(
			'following' => $id,
			'user_id'   => $user_id
		));
		if(empty($return)){
			throw new \Exception("User id does not exist. Please provide a valid id");
		}
		return $return;
	}
}