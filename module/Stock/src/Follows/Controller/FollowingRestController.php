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
			$result->id        = (int) $result->id;
			$result->user_id   = (int) $result->user_id;
			$result->following = (int) $result->following;

			//pegando os dados do following
			$followersData       = $this->getUserTable()->getUser($result->following);
			$result->followersId = $followersData->getArrayCopy();

			//convertendo tipo
			$result->followersId['id']      = (int) $result->followersId['id'];
			$result->followersId['reais']   = (float) $result->followersId['reais'];
			$result->followersId['dollars'] = (float) $result->followersId['dollars'];

			

			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
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