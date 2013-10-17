<?php
namespace Exchange\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Exchange\Model\Exchange;
use Exchange\Model\ExchangeTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

class ExchangeRestController extends AbstractRestfulController{

	protected $exchangeTable;

	public function getList(){
		$results = $this->getExchangeTable()->fetchAll();
		$data    = array();
		foreach ($results as $result) {
			$result->id = (int) $result->id;
			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function get($id){
		$exchange = $this->getExchangeTable()->getExchange($id);

		$exchange->id = (int) $exchange->id;

		return new JsonModel(array(
            'data' => $exchange,
        ));
		
	}

	public function create($data){
		throw new NotImplementedException("This method not exists");
	}

	public function update($id,$data){
		throw new NotImplementedException("This method not exists");
	}

	public function delete($id){
		throw new NotImplementedException("This method not exists");
	}

	public function getExchangeTable(){
		if(!$this->exchangeTable){
			$sm = $this->getServiceLocator();
			$this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
		}
		return $this->exchangeTable;
	}
}