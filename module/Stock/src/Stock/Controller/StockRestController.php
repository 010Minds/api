<?php
namespace Stock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use Exchange\Model\ExchangeTable;
use Zend\View\Model\JsonModel;
/**
 * example of @abstract usage in a class
 *
 * if even one method is declared abstract,
 * then the class itself should be also
 * @abstract
 */
class StockRestController extends AbstractRestfulController
{
	protected $stockTable;
	protected $exchangeTable;

	/**
     * O método getList pega a url /api/exchange/:id/:stock e api/stock
     * Nesta url /api/exchange/:id/:stock for final stock ele irá mostrar todas as ações da bolsa.
     * @return array Json
     * @version 0.2
     * @author Ezequiel Godoy
     */
	public function getList()
	{

		$requestParams = $this->params()->fromRoute(); 
		
		if(!empty($requestParams['stock']) && $requestParams['stock'] == 'stock'){
			$results 	   = $this->getStockTable()->getStockExchange($requestParams['uid']);
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
	/**
     * O método get pega a url api/stock/:id
     * Retorna os dados da bolsa selecionada
     * @return array Json
     * @version 0.1
     * @author Ezequiel Godoy
     */
	public function get($id)
	{
		$stock = $this->getStockTable()->getStock($id);
		$exchangeData = $this->getExchangeTable()->getExchange($stock->stockExchangeId);
		$stock->exchange = $exchangeData->getArrayCopy();

        return new JsonModel(array(
            'data' => $stock,
        ));
	}
	/**
     * O método getListExchangeStock faz o relacionamento com a table stock
     * @return array Json
     * @version 0.1
     * @author Alexsandro André
     */
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
	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     * @version 0.1
     * @author Ezequiel Godoy
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
     * @version 0.1
     * @author Alexsandro André
     */
	public function getExchangeTable(){
		if(!$this->exchangeTable){
			$sm = $this->getServiceLocator();
			$this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
		}
		return $this->exchangeTable;
	}
}