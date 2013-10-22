<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use User\Model\User;
use User\Form\UserForm;
use User\Model\UserTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;
use Application\Exception\UsersFormsException;

class UserRestController extends AbstractRestfulController
{
	protected $userTable;

	public function getList()
	{
		$results = $this->getUserTable()->fetchAll();
		$data = array();
		foreach ($results as $result) {
			$result->id      = (int) $result->id;
			$result->reais   = (float) $result->reais;
			$result->dollars = (float) $result->dollars;
			$data[] = $result;
		}

		// return array('data' => $result);
		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function get($id)
	{
		$user = $this->getUserTable()->getUser($id);

		// return array("data" => $user);

        return new JsonModel(array(
            'data' => $user,
        ));
	}

	public function create($data)
	{
	    $form  = new UserForm();
	    $user  = new User(); 
	    $model = new JsonModel(array());
	    
	    $form->setInputFilter($user->getInputFilter());
	    $form->setData($data);
	    
	    $id     = 0;
	    $return = array();
	    
	    if ($form->isValid()) { 
	        $user->exchangeArray($form->getData()); 
	        $id             = $this->getUserTable()->saveUser($user);
	        $return['data'] = $this->getUserTable()->getUser($id);
	    	$model->data    = $this->getUserTable()->getUser($id);
	    }else{
	    	$message = $form->getMessages();
	    	$model->header = array(
	    		'success' => false,
	    	);
	    	$model->errorMessage = $form->getMessages();
	    }
		return $model;
	}

	public function update($id, $data)
	{ 
		$model = new JsonModel(array());
	    $form  = new UserForm();

	    $data['id'] = $id;
	    $user = $this->getUserTable()->getUser($id);
	    
	    $form->bind($user);
	    $form->setInputFilter($user->getInputFilter());
	    $form->setData($data);
	    
	    if ($form->isValid()) {
	        $id = $this->getUserTable()->saveUser($form->getData());
	        $model->data = $this->getUserTable()->getUser($id);
	    }else{
	    	$message = $form->getMessages();
	    	$model->header = array(
	    		'success' => false,
	    	);
	    	$model->errorMessage = $form->getMessages();
	    }

	    return $model;
	}

	public function delete($id)
	{
		return new JsonModel(array(
			'data' => $this->getUserTable()->deleteUser($id),
		));
	}

	public function deleteList()
	{
		throw new NotImplementedException("This method not exists");
	}

	public function replaceList($data){
        throw new NotImplementedException("This method not exists");
    }

	public function getUserTable()
	{
		if(!$this->userTable){
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('User\Model\UserTable');
		}
		return $this->userTable;
	}
}