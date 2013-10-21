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
        $userId = $this->params()->fromRoute('uid', false);
        $results = $this->getNotificationTable()->getUserNotification($userId);

        $data = array();
        foreach ($results as $result) {
            $result->id             = (int) $result->id;
            $result->userId         = (int) $result->userId;
            $result->description    = $result->description;

            $data[] = $result;
        }

        return new JsonModel(array(
            'data' => $data,
        ));
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
        $userId = $this->params()->fromRoute('uid', false);
        $id     = $this->params()->fromRoute('id', false);

        $this->getNotificationTable()->deleteUserNotification($userId, $id);

        return new JsonModel(array(
            'data' => '',
        ));
    }
    public function deleteList()
    {
        $userId = $this->params()->fromRoute('uid', false);
        $this->getNotificationTable()->deleteUserNotification($userId);

        return new JsonModel(array(
            'data' => '',
        ));
    }
    // Table "Notification"
    public function getNotificationTable()
    {
        if(!$this->notificationTable){
            $sm = $this->getServiceLocator();
            $this->notificationTable = $sm->get('Notification\Model\NotificationTable');
        }
        return $this->notificationTable;
    }

}