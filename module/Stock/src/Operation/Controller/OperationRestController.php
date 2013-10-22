<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Form\OperationForm;
use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

class OperationRestController extends AbstractRestfulController
{
	protected $operationTable;

    public function getList()
    {
        $uid    = $this->params()->fromRoute('uid', false);
        $status = $this->params()->fromRoute('status', false);
        $type   = $this->params()->fromRoute('type', false);

        $results = $this->getOperationTable()->getOperations($uid, $status, $type);

        $data = array();
        foreach ($results as $result) {
            $result->id      = (int) $result->id;
            $result->userId  = (int) $result->userId;
            $result->stockId = (int) $result->stockId;
            $result->qtd     = (int) $result->qtd;
            $result->value   = (float) $result->value;
            $result->type    = (int) $result->type;
            $result->status  = (int) $result->status;

            $data[] = $result;
        }

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
        $form      = new OperationForm();
        $operation = new Operation();
        $model     = new JsonModel(array());

        $userId          = $this->params()->fromRoute('uid', false);
        $data['user_id'] = $userId;
        $data['value']   = (int) $data['value'];

        $form->setInputFilter($operation->getInputFilter());
        $form->setData($data);

        $id = 0;
        if($form->isValid()){
            $operation->exchangeArray($data);
            $id = $this->getOperationTable()->saveOperation($operation);

            $model->data = $this->getOperationTable()->get($id);
        }else{
            $message = $form->getMessages();
            $model->header = array(
                'success' => false,
            );
            $model->errorMessage = $form->getMessages();
        }

        return $model;
    }

    public function update($id,$data){
       throw new NotImplementedException("This method not exists");
    }

    public function replaceList($data){
        throw new NotImplementedException("This method not exists");
    }

    public function deleteList(){
        throw new NotImplementedException("This method not exists");
    }

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