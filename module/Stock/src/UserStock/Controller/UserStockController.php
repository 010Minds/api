<?php
namespace UserStock\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use UserStock\Model\UserStock;

class UserStockController extends AbstractActionController
{
	protected $userStockTable;

	public function indexAction()
	{
		return new ViewModel(array(
			'userStocks' => $this->getUserStockTable()->fetchAll(),
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

    public function getUserStockTable()
    {
        if(!$this->userStockTable){
            $sm = $this->getServiceLocator();
            $this->userStockTable = $sm->get('UserStock\Model\UserStockTable');
        }
        return $this->userStockTable;
    }

}