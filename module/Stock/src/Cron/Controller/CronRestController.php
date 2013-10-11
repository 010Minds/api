<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\View\Model\JsonModel;

class CronRestController extends AbstractRestfulController
{
	protected $OperationTable;

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
        Atualiza as operações pendentes no tempo determinado pelo Cron (ex: 30/30 min)

         - Verificar operações pendentes

        Pendentes para compra:
         - Verificar saldo do usuário e a disponibilidade de volume do stock antes de efetivar a compra. Regra: (lance x qtd) >= saldo
         - NASDAQ: Antes de comprar verificar o saldo em $(dolar).
           Caso o saldo seja insuficiente, avisar que deve-se converter o saldo em R$(reais) para dolar antes da compra
         - BOVESPA: Mesma regra que NASDAQ, porém convertendo $(dolar) em reais
         - Se lance do usuário for menor que o valor atual => Não efetiva a compra e altera o status para "rejected" e informar o motivo
         - Se o lance do usuário for maior ou igual ao valor de mercado e o volume do Stock maior ou igual ao volume requerido => Efetiva a compra.
            - Stats muda para "accepted"
            - Desconta o valor da compra do saldo do usário
            - Diminui o volume do stock
            - Inclui o Stock na tabela user_stock

        Pendentes para venda:
         - Se o valor para venda for maior que o valor de mercado => Não efetiva a venda e altera o status para "rejected" e informar o motivo
         - Se valor para venda for menor ou igual ao valor de mercado => Efetiva a venda
           - Stats muda para "accepted"
           - Inclui o valor da venda no saldo do usuário
           - Quantidade vendida retorna ao Stock
           - Retira o Stock vendido da tabela user_stock
    */
    public function replaceList($data){

        // Verifica Operações Pendentes
        $this->getOperationTable()->updateOperationPending();

        // Verifica Saldo do Usuário

        // Verifica Volume Disponível do Stock

        // Se tudo ocorreu corretamente
        // - Muda status da operação
        // - Desconta saldo do usuário
        // - Diminui volume do Stock
        // - Inclui Stock em user_stock

/*        $pendingOperations = $this->getOperationTable()->updateOperationPending();

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

    public function getOperationTable()
    {
        if(!$this->OperationTable){
            $sm = $this->getServiceLocator();
            $this->OperationTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->OperationTable;
    }

}