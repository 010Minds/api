<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\View\Model\JsonModel;

class OperationRestController extends AbstractRestfulController
{
	protected $operationTable;

    public function getList()
    {
        $idUser = $this->params()->fromRoute('idUser', false);
        $option = $this->params()->fromRoute('option', false);
        $type   = $this->params()->fromRoute('type', false);

        $results = $this->getOperationTable()->getOperations($idUser, $option, $type);

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
        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
        ));
    }
/*
    public function setIdentifierName($name)
    {
        $this->identifierName = (string) $name;
        //return $this;
        print($this); exit();
    }
*/
    public function create($data)
    {
        $operation = new Operation;
        $operation->exchangeArray($data);
        $id = $this->getOperationTable()->saveOperation($operation);

        return new JsonModel(array(
            'data' => $this->getOperationTable()->get($id),
        ));
    }
/*
    public function update($id, $data)
    {
    }
*/
    public function delete($id)
    {
        $this->getOperationTable()->deleteOperation($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }

    public function getOperationTable()
    {
        if(!$this->operationTable){
            $sm = $this->getServiceLocator();
            $this->operationTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->operationTable;
    }

}