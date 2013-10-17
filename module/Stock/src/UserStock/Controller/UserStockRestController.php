<?php
namespace UserStock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Stock\Model\StockTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

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

		//id do user
		$uid = $requestParams['uid'];

		$results 	= $this->getUserStockTable()->getStockUser($uid,'');
		$data       = array();
		$stockData  = array();
		foreach ($results as $result) {
			$result->id      = (int) $result->id;
			$result->userId  = (int) $result->userId;
			$result->stockId = (int) $result->stockId;
			$result->value   = (float) $result->value;

			$stockData = $this->getStockTable()->getStock($result->stockId);
			$result->stock = $stockData->getArrayCopy();
			$result->stock['id']              = (int) $result->stock['id'];
			$result->stock['current']         = (float) $result->stock['current'];
			$result->stock['open'] 			  = (float) $result->stock['open'];
			$result->stock['high'] 			  = (float) $result->stock['high'];
			$result->stock['low'] 			  = (float) $result->stock['low'];
			$result->stock['percent'] 		  = (float) $result->stock['percent'];
			$result->stock['country'] 		  = (int) $result->stock['country'];
			$result->stock['stockExchangeId'] = (int) $result->stock['stockExchangeId'];
			$result->stock['volume'] 		  = (float) $result->stock['volume'];

			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	/**
     * O método get pega a url /api/user/:uid/:my-stock/:id (teste)
     *
     * @api
     * @return array json_encode
     */
	public function get($id)
	{
		$requestParams = $this->params()->fromRoute();
		//id do user
		$uid           = $requestParams['uid'];

		$results = $this->getUserStockTable()->getStockUser($uid,$id);

		$data       = array();
		$stockData  = array();
		foreach ($results as $result) {
			$result->id      = (int) $result->id;
			$result->userId  = (int) $result->userId;
			$result->stockId = (int) $result->stockId;
			$result->value   = (float) $result->value;

			$stockData = $this->getStockTable()->getStock($result->stockId);
			$result->stock = $stockData->getArrayCopy();
			$result->stock['id']              = (int) $result->stock['id'];
			$result->stock['current']         = (float) $result->stock['current'];
			$result->stock['open'] 			  = (float) $result->stock['open'];
			$result->stock['high'] 			  = (float) $result->stock['high'];
			$result->stock['low'] 			  = (float) $result->stock['low'];
			$result->stock['percent'] 		  = (float) $result->stock['percent'];
			$result->stock['country'] 		  = (int) $result->stock['country'];
			$result->stock['stockExchangeId'] = (int) $result->stock['stockExchangeId'];
			$result->stock['volume'] 		  = (float) $result->stock['volume'];

			$data = $result;
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
		throw new NotImplementedException("This method not exists");
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
		throw new NotImplementedException("This method not exists");
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