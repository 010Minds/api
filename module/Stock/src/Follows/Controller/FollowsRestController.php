<?php
namespace Follows\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

class FollowsRestController extends AbstractRestfulController{

	protected $followsTable;
	protected $userTable;

	/**
	 * Método que lista os followers pegando id por request
	 * @return array $data
	 */
	public function getList(){
		$requestParams = $this->params()->fromRoute();
		$requestUri    = $this->getRequest()->getUri(); 
		
		//procurando a palavra chave following na request passada
		$followingUri = $this->getPartsUri($requestUri,'following');
		//procurando a palavra chave followers na request passada
		$followersUri = $this->getPartsUri($requestUri,'followers');
		
		if(!empty($followingUri) && $followingUri == 'following' && !empty($requestParams['uid'])){
			$results = $this->getFollowsTable()->getFollowing($requestParams['uid']);
		}
		else if(!empty($requestParams['uid']) && !empty($followersUri) && $followersUri == 'followers'){
			$results = $this->getFollowsTable()->getFollowers($requestParams['uid']);
		}else {
			$this->response->setStatusCode(404);
			return new JsonModel();
		}
		$data          = array();
		$followersData = array();
		foreach ($results as $result) {
			//convertendo tipo
			$result->id        = (int) $result->id;
			$result->user_id   = (int) $result->user_id;
			$result->following = (int) $result->following;
			
			if(!empty($followingUri) && $followingUri == 'following' && !empty($requestParams['uid'])){
				//pegando os dados do following
				$followersData       = $this->getUserTable()->getUser($result->following);
				$result->followersId = $followersData->getArrayCopy();
			}
			else{
				//pegando os dados do follower
				$followersData       = $this->getUserTable()->getUser($result->user_id);
				$result->followersId = $followersData->getArrayCopy();
			}

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

	public function get($id){
		
		/*$follow = $this->getFollowsTable()->getFollows($id);

		return new JsonModel(array(
            'data' => $follow,
        ));*/
		
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