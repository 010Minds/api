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

	/**
	 * Responsável por cadastrar a listas de follows
	 * @param objeto Follows
	 * @return int $id
	 */
	public function follow(Follows $follows){
		$data = array(
			'user_id' 	=> $follows->user_id,
			'following'	=> $follows->following,
		);
		
		$id = (int) $follows->id;
		
		$this->tableGateway->insert($data);
		$id = $this->tableGateway->getLastInsertValue();

		return $id;
	}

	/**
	 * Método resposável por executar unfollow (deixar de seguir)
	 * @param int $user_id id do usuário
	 * @param int $id id do following a ser deletado
	 */
	public function deleteFollow($user_id,$id){
		$this->tableGateway->delete(array(
			'following' => $id,
			'user_id'   => $user_id
		));
	}
}