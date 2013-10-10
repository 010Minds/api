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
}