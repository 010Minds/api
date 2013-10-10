<?php
namespace UserStock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Stock\Model\StockTable;
use Zend\View\Model\JsonModel;

class UserStockRestController extends AbstractRestfulController
{
	protected $userStockTable;
	protected $stockTable;

	/**
     * O método getList pega a url /api/user/:uid/:my-stock
     * @api
     * @return array json_encode
     */
	public function getList()
	{

		$requestParams = $this->params()->fromRoute();


		if(!empty($requestParams['my-stock']) && $requestParams['my-stock'] == 'my-stock'){
			$results 	   = $this->getUserStockTable()->getStockUser($requestParams['uid'],'');
		}
		else if(empty($requestParams['my-stock'])) {
			$results       = $this->getStockTable()->fetchAll();
		} else {
			$this->response->setStatusCode(404);
			return new JsonModel();
		}

		$data       = array();
		$stockData  = array();
		foreach ($results as $result) {
			$stockData = $this->getStockTable()->getStock($result->stockId);
			$result->stock = $stockData->getArrayCopy();
			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
     * O método get pega a url /api/user/:uid/:my-stock/:id
     *
     * @api
     * @return array json_encode
     */
	public function get($id)
	{

		$requestParams = $this->params()->fromRoute();

		//verifica se a url my-stock está setada e se a variavel uid não está vazio
		if(!empty($requestParams['my-stock']) && $requestParams['my-stock'] == 'my-stock' && !empty($requestParams['uid'])){
			$results = $this->getUserStockTable()->getStockUser($requestParams['uid'],$requestParams['id']);
		}else{
			$this->response->setStatusCode(404);
			return new JsonModel();
		}
		$data       = array();
		$stockData  = array();
		foreach ($results as $result) {
			$stockData = $this->getStockTable()->getStock($result->stockId);
			$result->stock = $stockData->getArrayCopy();
			$data[] = $result;
		}
        return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function getListStockUser($userId)
	{
		$results = $this->getUserStockTable()->listStockUser($userId);
		$data = array();
		foreach ($results as $result) {
			$data[] = $result;
		}

		// return array('data' => $result);
		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getStockTable(){
		if(!$this->stockTable){
			$sm = $this->getServiceLocator();
			$this->stockTable = $sm->get('Stock\Model\StockTable');
		}
		return $this->stockTable;
	}

	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function delete($id)
	{
		$requestParams = $this->params()->fromRoute();
		//verifica se a url my-stock está setada e se a variavel uid não está vazio
		if(!empty($requestParams['my-stock']) && $requestParams['my-stock'] == 'my-stock' && !empty($requestParams['uid'])){
			//$requestParams['uid'] do user_id
			$this->getUserStockTable()->deleteUserStock($id,$requestParams['uid']);

			return new JsonModel(array(
				'data' => 'deleted',
			));
		}
		else{
			$this->response->setStatusCode(404);
			return new JsonModel();
		}
	}

	public function create($data)
	{
/*	    $form = new Form();
	    $UserStock = new UserStock();
	    $form->setInputFilter($userStock->getInputFilter());
	    $form->setData($data);
	    $id = 0;
	    if ($form->isValid()) {
	        $userStock->exchangeArray($form->getData());
	        $id = $this->getUserStockTable()->saveUserStock($userStock);
	    }

	    return new JsonModel(array(
	        'data' => $this->getStockTable()->getStock($id),
	    ));*/
	}

	public function update($id, $data)
	{
/*	    $data['id'] = $id;
	    $userStock = $this->getUserStockTable()->getUserStock($id);
	    $form  = new UserStockForm();
	    $form->bind($userStock);
	    $form->setInputFilter($userStock->getInputFilter());
	    $form->setData($data);
	    if ($form->isValid()) {
	        $id = $this->getUserStockTable()->saveUserStock($form->getData());
	    }

	    return new JsonModel(array(
	        'data' => $this->getStockTable()->getStock($id),
	    ));*/
	}



	public function getUserStockTable()
	{
		if(!$this->userStockTable){
			$sm = $this->getServiceLocator();
			$this->userStockTable = $sm->get('UserStock\Model\UserStockTable');
		}
		return $this->userStockTable;
	}
}