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
		$userId          = $this->params()->fromRoute('uid', false);
		
		return new JsonModel(array(
            'data' => $this->getMyProfile($userId),
        ));
	}
	
	public function get($id){
		$requestParams   = $this->getRequest()->getUri();
		$userUri         = $this->getPartsUri($requestParams,'user');
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

		$data = array(
			'user'       => $results,
			'follower'  => $followers,
			'following' => $followings,
		);

		if(!empty($userUri) && $userUri == 'user'){
			//get list followings
			$dataFollowings = $this->getFollowsTable()->getFollowing($id);
			$i = 0;
			foreach ($dataFollowings as $value) {
				if($i <= 9){
					$value->id        = (int) $value->id;
					$value->user_id   = (int) $value->user_id;
					$value->following = (int) $value->following;
					//mudar para retornar o user
					$followingsArray += array('user_'.$value->following => $value);
					$i++;	
				}
			}

			$data['followings'] = $followingsArray;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
     * O método complemento do getList faz retorna o perfil do user contendo as informações como:
     * follower,following e lista de followings
     * @return array json_encode
     */
	public function getMyProfile($id){
		$data            = array();
		$followingsArray = array();
		
		//get user
		$results = $this->getUserTable()->getUser($id);
		
		//retirando o campo password não é necessario retorna-lo
		unset($results->password);

		$results->id             = (int) $results->id; 
		$results->reais          = (float) $results->reais;
		$results->dollars        = (float) $results->dollars; 
		$results->public_profile = (boolean) $results->public_profile; 
		
		//get total followers
		$followers      = $this->getFollowsTable()->fullFollowers($id);
		//get total followings
		$followings     = $this->getFollowsTable()->fullFollowing($id);

		//get list followings
		$dataFollowings = $this->getFollowsTable()->getFollowing($id);
		$i = 0;
		foreach ($dataFollowings as $value) {
			//retirando alguns dados desnecessários
			unset($value->id);
			unset($value->user_id);
			unset($value->created_date);
			unset($value->user);

			if($i <= 9){
				$value->following = (int) $value->following;
				$resultUser = $this->getUserTable()->getUser($value->following);
				//retirando o campo password não é necessario retorna-lo
				unset($resultUser->password);

				$resultUser->id             = (int) $resultUser->id; 
				$resultUser->public_profile = (boolean) $resultUser->public_profile;

				//teste se o following aceitou a amizade
				if($value->permission == true){
					$resultUser->reais          = (float) $resultUser->reais;
					$resultUser->dollars 	  	= (float) $resultUser->dollars;
				}else{
					//teste se o perfil é publico/privado
					if($resultUser->public_profile == false){
						//retirando alguns dados desnecessários
						unset($resultUser->dollars);
						unset($resultUser->reais);
						unset($resultUser->mail);
						unset($resultUser->user);
					}
				}
				$resultUser->permission     = (boolean) $value->permission;
				
				$followingsArray += array('user_'.$value->following => $resultUser);
				$i++;	
			}
		}

		$data = array(
			'user'       => $results,
			'follower'   => $followers,
			'following'  => $followings,
			'followings' => $followingsArray,
		);

		return $data;
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
	/**
	 * Sua função é verificar se existe a palavra chave na uri e retorna-la
	 * @param String $requestUri uri acessada pelo usuário
	 * @param String $chave palavra que desejada a ser encontrada na uri
	 * @return String $chave 
	 */
	public function getPartsUri($requestUri,$chave){
		
		if(empty($requestUri) || empty($chave)){
			return false;
		}
		
		$vetor    = explode('/',$requestUri);
		$arrayKey = array_search($chave, $vetor);
		return $vetor[$arrayKey];
	}
}