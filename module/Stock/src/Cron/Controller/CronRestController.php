<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\View\Model\JsonModel;

class CronRestController extends AbstractRestfulController
{
	protected $cronTable;

    public function getList()
    {
        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
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

        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
        ));
    }

    public function update($id, $data)
    {
        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
        ));
    }

    /*
        Atualiza a tabela de operações
    */
    public function replaceList($data){

        $this->getCronTable()->updateOperationPending();

/*        $pendingOperations = $this->getCronTable()->updateOperationPending();

        for ($pendingOperations) {
            if($buy) {
                $boo = $getuserTable->validateBuySaldo($operation);
                if ($boo (tem saldo)) {

                }

            } else if ($sell) {

            }
        }*/


        return new JsonModel(array(
            'data' => 'not-implemented',
        ));

    }

    public function delete($id)
    {
        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
        ));
    }

    public function getCronTable()
    {
        if(!$this->cronTable){
            $sm = $this->getServiceLocator();
            $this->cronTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->cronTable;
    }

}