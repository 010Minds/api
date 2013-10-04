<?php
namespace UserStock\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

class UserStockRestController extends AbstractRestfulController
{
	protected $userStockTable;

	public function getList()
	{
		$results = $this->getUserStockTable()->fetchAll();
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
		$userStock = $this->getUserStockTable()->getUserStock($id);

		$data = array();
		foreach ($userStock as $result) {
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

	public function delete($id)
	{
/*		$this->getUserStockTable()->deleteUserStock($id);

		return new JsonModel(array(
			'data' => 'deleted',
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