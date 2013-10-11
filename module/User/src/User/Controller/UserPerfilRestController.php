<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use User\Model\User;
use User\Form\UserForm;
use User\Model\UserTable;
use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use Zend\View\Model\JsonModel;

/**
 * classe que gerencia o perfil do usuário
 *
 * @api
 * @author Alexsandro André <andre@010minds.com>
 * 
 */
class UserPerfilRestController extends AbstractRestfulController{
	
	protected $userTable;
	protected $followsTable;

	/**
     * O método getList faz retorna o perfil do user contendo as informações como:
     * follower,following e lista de followings
     * @return array json_encode
     */
	public function getList(){ 
		#code
	}

	public function get($id){
		$requestParams   = $this->params()->fromRoute(); 
		//$userid          = $requestParams['uid'];
		$profile         = $requestParams['profile'];
		$data            = array();
		$followingsArray = array();

		//get user
		$results = $this->getUserTable()->getUser($id);
		
		$results->id      = (int) $results->id; 
		$results->reais   = (float) $results->reais;
		$results->dollars = (float) $results->dollars; 
		
		//get total followers
		$followers      = $this->getFollowsTable()->fullFollowers($id);
		//get total followings
		$followings     = $this->getFollowsTable()->fullFollowing($id);
		//get list followings
		$dataFollowings = $this->getFollowsTable()->getFollowing($id);

		$i = 0;
		foreach ($dataFollowings as $value) {
			if($i <= 9){
				$value->id        = (int) $value->id;
				$value->user_id   = (int) $value->user_id;
				$value->following = (int) $value->following;

				$followingsArray[] = array('user' => $value);
				$i++;	
			}
		}

		$data = array(
			'user'       => $results,
			'follower'  => $followers,
			'following' => $followings,
			'followings' => $followingsArray,
		);

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
     * O método getUserTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getUserTable(){
		if(!$this->userTable){
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('User\Model\UserTable');
		}
		return $this->userTable;
	}

	/**
     * O método getFollowsTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getFollowsTable(){
		if(!$this->followsTable){
			$sm = $this->getServiceLocator();
			$this->followsTable = $sm->get('Follows\Model\FollowsTable');
		}
		return $this->followsTable;
	}
}