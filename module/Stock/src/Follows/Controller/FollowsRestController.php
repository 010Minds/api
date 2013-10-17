<?php
namespace Follows\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use Follows\Form\FollowsForm;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

class FollowsRestController extends AbstractRestfulController{

	protected $followsTable;
	protected $userTable;

	
	public function getList(){
		throw new NotImplementedException("This method not exists");
	}

	/**
	 * Método que lista os followers pegando id por request
	 * @return array $data
	 */
	public function get($id){
		$id         = (int) $id;
		$data       = array();
		$request    = $this->getRequest()->getUri();
		$pendingUri = $this->getPartsUri($request,'pending');
		$unfollowUri = $this->getPartsUri($request,'unfollow');

		//lista as pendencias do user
		if(!empty($pendingUri) && $pendingUri == 'pending'){
			$data = $this->listPending($id);
		}else{
			if(empty($unfollowUri) && $unfollowUri != 'unfollow' ){
				$data = $this->listFollowers($id);	
			}else{
				throw new NotImplementedException("This method not exists");
			}
		}
		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
	 * Lista todos os followers do user (é complemento do método)
	 * @param int $id do user 
	 * @return array $data
	 */
	public function listFollowers($id){ 
		$results       = $this->getFollowsTable()->getFollowers($id);	
		$data          = array();
		$followersData = array();
		
		foreach ($results as $result) {
			//convertendo tipo
			$result->id         = (int) $result->id;
			$result->user_id    = (int) $result->user_id;
			$result->following  = (int) $result->following;
			$result->permission = (boolean) $result->permission;
			
			//pegando os dados do follower
			$followersData = $this->getUserTable()->getUser($result->user_id);
			$result->user  = $followersData->getArrayCopy();
			if($result->permission == true){
				//convertendo tipo
				$result->user['id']      = (int) $result->user['id'];
				$result->user['reais']   = (float) $result->user['reais'];
				$result->user['dollars'] = (float) $result->user['dollars'];
			}else{
				unset($result->user['id']);
				unset($result->user['mail']);
				unset($result->user['user']);
				unset($result->user['password']);
				unset($result->user['reais']);
				unset($result->user['dollars']);
			}

			$data[] = $result;
		}
		return $data;
	}

	/**
	 * Método que lista os followers pendentes(aguardando a aprovação do user)
	 * @param int $id do user 
	 * @return array $data
	 */
	public function listPending($id)
	{ 
		$data    = array();
		$results = $this->getFollowsTable()->getPending($id);

		foreach($results as $result){
			//convertendo tipo
			$result->id         = (int) $result->id;
			$result->user_id    = (int) $result->user_id;
			$result->following  = (int) $result->following;
			$result->permission = (boolean) $result->permission;

			//pegando os dados do follower
			$followersData = $this->getUserTable()->getUser($result->user_id);
			$result->user  = $followersData->getArrayCopy();

			//convertendo tipo
			$result->user['id']      = (int) $result->user['id'];
			$result->user['reais']   = (float) $result->user['reais'];
			$result->user['dollars'] = (float) $result->user['dollars'];

			$data[] = $result;
		}
		return $data;
	}

	/**
	 * Método que cadastra os follows - (quem estou seguindo)
	 * @return array $data
	 */
	public function create($data)
	{
		$request     = $this->getRequest()->getUri();
		$unfollowUri = $this->getPartsUri($request,'unfollow');
		if(empty($unfollowUri) && $unfollowUri != 'unfollow' ){
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
		}else{
			throw new NotImplementedException("This method not exists");
		}
	}

	/**
	 * Método responsável pelo unfollow (deixar de seguir)
	 * @param int $id id do follow a ser deletado
	 * @param int $user_id get por request id do user
	 * @return array json_encode 
	 */
	public function delete($id)
	{
		$request     = $this->getRequest()->getUri();
		$unfollowUri = $this->getPartsUri($request,'unfollow');
		
		if(!empty($unfollowUri) && $unfollowUri == 'unfollow'){
			//id do follow (seguindo)
			$id      =  (int) $id;   
			//id do user
			$user_id =  (int) $this->params()->fromRoute('uid', false);
			$return  = $this->getFollowsTable()->deleteFollow($user_id,$id);
			
			return new JsonModel(array(
				'data' => $return,
			));
		}else{
			throw new NotImplementedException("This method not exists");
		}
	}

	public function replaceList($data)
	{
        throw new NotImplementedException("This method not exists");
    }

    public function deleteList()
	{
		throw new NotImplementedException("This method not exists");
		
	}

	public function update($id,$data)
	{
		throw new NotImplementedException("This method not exists");
		
	}

	public function getFollowsTable()
	{
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
	public function getUserTable()
	{
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
	public function getPartsUri($requestUri,$chave)
	{
		if(empty($requestUri) || empty($chave)){
			return false;
		}
		
		$vetor    = explode('/',$requestUri);
		$arrayKey = array_search($chave, $vetor);
		return $vetor[$arrayKey];
	}
}