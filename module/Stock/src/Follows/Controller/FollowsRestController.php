<?php
namespace Follows\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use Follows\Form\FollowsForm;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

class FollowsRestController extends AbstractRestfulController{

	protected $followsTable;
	protected $userTable;

	
	public function getList(){
		#code...
	}

	/**
	 * Método que lista os followers pegando id por request
	 * @return array $data
	 */
	public function get($id){
		$id = (int) $id;

		$results = $this->getFollowsTable()->getFollowers($id);	

		$data          = array();
		$followersData = array();
		foreach ($results as $result) {
			//convertendo tipo
			$result->id        = (int) $result->id;
			$result->user_id   = (int) $result->user_id;
			$result->following = (int) $result->following;
			
			//pegando os dados do follower
			$followersData       = $this->getUserTable()->getUser($result->user_id);
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

	/**
	 * Método que cadastra os follows - (quem estou seguindo)
	 * @return array $data
	 */
	public function create($data){
		//id do user
		$data['user_id'] =  (int) $this->params()->fromRoute('uid', false);
        
        $form    = new FollowsForm();
	    $follows = new Follows();

		$form->setInputFilter($follows->getInputFilter());
	    $form->setData($data);
	    
	    $id = 0;
	    if ($form->isValid()) { 
	        $follows->exchangeArray($form->getData());
	        $id = $this->getFollowsTable()->follow($follows);
	    }
	    
	    return new JsonModel(array(
	        'data' => $this->getFollowsTable()->getObjFollow($id),
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