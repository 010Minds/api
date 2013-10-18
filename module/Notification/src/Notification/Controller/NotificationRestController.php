<?php
namespace Notification\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Notification\Model\Notification;
use Notification\Model\NotificationTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

class NotificationRestController extends AbstractRestfulController
{
	protected $notificationTable;

    public function getList()
    {
        throw new NotImplementedException("This method not exists");
    }

    public function get($id)
    {
        throw new NotImplementedException("This method not exists");
    }

    public function create($data)
    {
        throw new NotImplementedException("This method not exists");
    }

    public function update($id, $data)
    {
        throw new NotImplementedException("This method not exists");
    }

    public function replaceList($data)
    {
        throw new NotImplementedException("This method not exists");
    }

    public function delete($id)
    {
        throw new NotImplementedException("This method not exists");
    }

    // Table "Notification"
    public function getOperationTable()
    {
        if(!$this->operationTable){
            $sm = $this->getServiceLocator();
            $this->operationTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->operationTable;
    }

}