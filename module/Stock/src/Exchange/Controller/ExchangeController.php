<?php
namespace Exchange\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Exchange\Model\Exchange;

class ExchangeController extends AbstractActionController{

	protected $exchangeTable;

	public function indexAction(){
		return new ViewModel(array(
			'exchange' => $this->getExchangeTable()->fetchAll(),
		));
	}

	public function getExchangeTable(){
        if(!$this->exchangeTable){
            $sm = $this->getServiceLocator();
            $this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
        }
        return $this->exchangeTable;
    }
}