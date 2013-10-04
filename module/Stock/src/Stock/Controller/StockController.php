<?php
namespace Stock\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Stock\Model\Stock;

class StockController extends AbstractActionController
{
	protected $stockTable;

	public function indexAction()
	{
		return new ViewModel(array(
			'stocks' => $this->getStockTable()->fetchAll(),
		));
	}


    public function addAction()
    {
        # Code
    }

    public function editAction()
    {
        # Code
    }

    public function deleteAction()
    {
        # Code
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