<?php
namespace Stock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use Zend\View\Model\JsonModel;

class StockRestController extends AbstractRestfulController
{
	protected $stockTable;

	public function getList()
	{
		$results = $this->getStockTable()->fetchAll();
		$data = array();
		foreach ($results as $result) {
			$data[] = $result;
		}

		// return array('data' => $result);
		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function get($id)
	{
		$stock = $this->getStockTable()->getStock($id);

		// return array("data" => $stock);

        return new JsonModel(array(
            'data' => $stock,
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
}