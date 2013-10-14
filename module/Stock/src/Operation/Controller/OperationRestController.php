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
        $userId = $this->params()->fromRoute('uid', false);
        $option = $this->params()->fromRoute('option', false);
        $type   = $this->params()->fromRoute('type', false);

        $results = $this->getOperationTable()->getOperations($userId, $option, $type);
        
        $data = array();
        foreach ($results as $result) {
            $result->id     = (int) $result->id;
            $result->userId = (int) $result->userId;
            $result->value  = (float) $result->value;
            
            $data[] = $result;
        }

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function get($id)
    {
        die('here');
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

        $userId = $this->params()->fromRoute('userId', false);
        $data['user_id'] = $userId;

        // TODO: When value is 0, get value from Stock
        if ($data['value'] == 0) {
            $data['value'] = 1;
        }

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
        $userId = $this->params()->fromRoute('userId', false);

        $this->getOperationTable()->deleteOperation($id, $userId);

        return new JsonModel(array(
            'data' => 'deleted', // Necessário validar se foi apagado com sucesso e retornar erro caso negativo
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