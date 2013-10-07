<?php
namespace Stock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use Exchange\Model\ExchangeTable;
use Zend\View\Model\JsonModel;

class StockRestController extends AbstractRestfulController
{
	protected $stockTable;
	protected $exchangeTable;

	public function getList()
	{

		$results       = $this->getStockTable()->fetchAll();
		$data          = array();
		$exchangeData  = array();
		foreach ($results as $result) {
			$exchangeData = $this->getExchangeTable()->getExchange($result->stockExchangeId);
			$result->exchange = $exchangeData->getArrayCopy();
			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function get($id)
	{
		
		$requestParams = $this->params()->fromRoute(); 
		
		if($requestParams['stock'] == 'stock'){
			$results 	   = $this->getStockTable()->getStockExchange($id);
			$data   	   = array();
			$exchangeData  = array();
			foreach ($results as $result) {
				$exchangeData = $this->getExchangeTable()->getExchange($result->stockExchangeId);
				$result->exchange = $exchangeData->getArrayCopy();
				$data[] = $result;
			}

			return new JsonModel(array(
				'data' => $data,
			));
		}

		$stock = $this->getStockTable()->getStock($id);
		$exchangeData = $this->getExchangeTable()->getExchange($stock->stockExchangeId);
		$stock->exchange = $exchangeData->getArrayCopy();

        return new JsonModel(array(
            'data' => $stock,
        ));
	}

	public function getListExchangeStock(){
		$stock = $this->getStockTable()->getStock($id);
		$exchangeData = $this->getExchangeTable()->getExchange($stock->stockExchangeId);
		$stock->exchange = $exchangeData->getArrayCopy();

        return new JsonModel(array(
            'datas' => $stock,
        ));
	}

	public function create($data)
	{
/*	    $form = new Form();
	    $stock = new Stock();
	    $form->setInputFilter($stock->getInputFilter());
	    $form->setData($data);
	    $id = 0;
	    if ($form->isValid()) {
	        $stock->exchangeArray($form->getData());
	        $id = $this->getStockTable()->saveStock($stock);
	    }

	    return new JsonModel(array(
	        'data' => $this->getStockTable()->getStock($id),
	    ));*/
	}

	public function update($id, $data)
	{
/*	    $data['id'] = $id;
	    $stock = $this->getStockTable()->getStock($id);
	    $form  = new StockForm();
	    $form->bind($stock);
	    $form->setInputFilter($stock->getInputFilter());
	    $form->setData($data);
	    if ($form->isValid()) {
	        $id = $this->getStockTable()->saveStock($form->getData());
	    }

	    return new JsonModel(array(
	        'data' => $this->getStockTable()->getStock($id),
	    ));*/
	}

	public function delete($id)
	{
/*		$this->getStockTable()->deleteStock($id);

		return new JsonModel(array(
			'data' => 'deleted',
		));*/
	}

	public function getStockTable()
	{
		if(!$this->stockTable){
			$sm = $this->getServiceLocator();
			$this->stockTable = $sm->get('Stock\Model\StockTable');
		}
		return $this->stockTable;
	}

	public function getExchangeTable(){
		if(!$this->exchangeTable){
			$sm = $this->getServiceLocator();
			$this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
		}
		return $this->exchangeTable;
	}
}