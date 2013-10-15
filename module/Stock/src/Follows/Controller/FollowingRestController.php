<?php
namespace Follows\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

class FollowingRestController extends AbstractRestfulController{

	protected $followsTable;
	protected $userTable;

	/**
	 * Método que lista os followers pegando id por request
	 * @return array $data
	 */
	public function getList(){
		#code ..
	}

	public function get($id){
		$id = (int) $id;

		$results = $this->getFollowsTable()->getFollowing($id);

		$data          = array();
		$followersData = array();
		foreach ($results as $result) {
			//convertendo tipo
			$result->id         = (int) $result->id;
			$result->user_id    = (int) $result->user_id;
			$result->following  = (int) $result->following;
			$result->permission = (boolean) $result->permission;

			//pegando os dados do following
			$followersData = $this->getUserTable()->getUser($result->following);
			$result->user  = $followersData->getArrayCopy();
			if($result->permission == true){
				//convertendo tipo
				$result->user['id']      = (int) $result->user['id'];
				$result->user['reais']   = (float) $result->user['reais'];
				$result->user['dollars'] = (float) $result->user['dollars'];
			}
			else{
				unset($result->user['id']);
				unset($result->user['mail']);
				unset($result->user['user']);
				unset($result->user['password']);
				unset($result->user['reais']);
				unset($result->user['dollars']);
			}

			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function create($data){
		die('here');
	}

	public function getFollowsTable(){
		if(!$this->followsTable){
			$sm = $this->getServiceLocator();
			$this->followsTable = $sm->get('Follows\Model\FollowsTable');
		}
		return $this->followsTable;
	}

	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getUserTable(){
		if(!$this->userTable){
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('User\Model\UserTable');
		}
		return $this->userTable;
	}
	
}