<?php
namespace Stock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use Exchange\Model\ExchangeTable;
use Zend\View\Model\JsonModel;

/**
 * StockRestController classe que gerencia as ações
 *
 * @author Alexsandro André <andre@010minds.com>
 * 
 */
class StockRestController extends AbstractRestfulController
{
	protected $stockTable;
	protected $exchangeTable;

	/**
     * O método getList pega a url /api/exchange/:id/:stock e api/stock
     * @api 
     * @return array json_encode
     */
	public function getList()
	{

		$requestParams = $this->params()->fromRoute(); 
		
		if(!empty($requestParams['stock']) && $requestParams['stock'] == 'stock'){
			$results 	   = $this->getStockTable()->getStockExchange($requestParams['uid']);
		}
		else if(empty($requestParams['stock'])) {
			$results       = $this->getStockTable()->fetchAll();
		} else {
			$this->response->setStatusCode(404);
			return new JsonModel();
		}
		$data          = array();
		$exchangeData  = array();
		foreach ($results as $result) {
			$result->id 	 		 = (int) $result->id;
			$result->current 		 = (float) $result->current;
			$result->open 	 		 = (float) $result->open;
			$result->high 	 		 = (float) $result->high;
			$result->low 	 		 = (float) $result->low;
			$result->percent 		 = (float) $result->percent;
			$result->stockExchangeId = (int) $result->stockExchangeId;

			$exchangeData     = $this->getExchangeTable()->getExchange($result->stockExchangeId);
			$result->exchange = $exchangeData->getArrayCopy();
			$result->exchange['id'] = (int) $result->exchange['id'];

			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}
	/**
     * O método get pega a url api/stock/:id
     * Retorna os dados da bolsa selecionada
     * @param int $id do stock.
     * @return array Json
     * 
     */
	public function get($id)
	{
		$stock = $this->getStockTable()->getStock($id);
		$stock->id 	 		     = (int) $stock->id;
		$stock->current 		 = (float) $stock->current;
		$stock->open 	 		 = (float) $stock->open;
		$stock->high 	 		 = (float) $stock->high;
		$stock->low 	 		 = (float) $stock->low;
		$stock->percent 		 = (float) $stock->percent;
		$stock->stockExchangeId  = (int) $stock->stockExchangeId;

		$exchangeData = $this->getExchangeTable()->getExchange($stock->stockExchangeId);
		$stock->exchange = $exchangeData->getArrayCopy();
		$stock->exchange['id'] = (int) $stock->exchange['id'];

        return new JsonModel(array(
            'data' => $stock,
        ));
	}
	/**
     * O método getListExchangeStock faz o relacionamento com a table stock
     * @return array Json
     */
	public function getListExchangeStock(){
		$stock = $this->getStockTable()->getStock($id);
		$exchangeData = $this->getExchangeTable()->getExchange($stock->stockExchangeId);
		$stock->exchange = $exchangeData->getArrayCopy();

        return new JsonModel(array(
            'datas' => $stock,
        ));
	}

	
	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getStockTable()
	{
		if(!$this->stockTable){
			$sm = $this->getServiceLocator();
			$this->stockTable = $sm->get('Stock\Model\StockTable');
		}
		return $this->stockTable;
	}
	/**
     * O método getExchangeTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getExchangeTable(){
		if(!$this->exchangeTable){
			$sm = $this->getServiceLocator();
			$this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
		}
		return $this->exchangeTable;
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
}