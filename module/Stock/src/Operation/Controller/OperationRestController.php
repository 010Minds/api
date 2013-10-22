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
        $uid    = $this->params()->fromRoute('uid', false);
        $option = $this->params()->fromRoute('option', false);
        $type   = $this->params()->fromRoute('type', false);

        $results = $this->getOperationTable()->getOperations($uid, $option, $type);
var_dump($uid); exit();
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

        $uid = $this->params()->fromRoute('uid', false);
        $data['user_id'] = $uid;

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
        $uid = $this->params()->fromRoute('uid', false);

        $this->getOperationTable()->deleteOperation($id, $uid);

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