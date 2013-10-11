<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\View\Model\JsonModel;

class CronRestController extends AbstractRestfulController
{
	protected $operationTable;
    protected $exchangeTable;
    protected $userTable;

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

        // Busca as Operações Pendentes
        $resultSetOperation = $this->getOperationTable()->getOperationsStatus('pending');

        $data = array();
        foreach ($resultSetOperation as $resultOperation) {

            // Busca os dados da stock_exchange do stock atual
            $resultSetExchange = $this->getExchangeTable()->getExchange($resultOperation->stockId);

            // Verifica a moeda que o stock está trabalhando
            switch ($resultSetExchange->currency) {

                case '$':
                    $currency = 'dollars';
                    break;

                case 'R$':
                    $currency = 'reais';
                    break;

                default:
                    throw new \Exception("Currency invalid");
                    break;
            }

            // Busca dados do usuario
            $resultSetUser = $this->getUserTable()->getUser($resultOperation->userId);

            // Verifica se usuario tem saldo suficiente
            $dollarsSaldo = $resultSetUser->dollars;
            $reaisSaldo = $resultSetUser->reais;
            $lance = $resultOperation->qtd * $resultOperation->value;

            if(${$currency.'Saldo'} >= $lance){
                $msg = '[ Possui saldo ] lance -> ' . $lance . ' : saldo -> ' . ${$currency.'Saldo'}."\n";

                /*
                    - Altera 'status' da "operation" para 'accepted'
                    - Altera 'reason' para 'Rejeitado por falta de saldo'
                */
            }
            else{
                $msg = '[ Saldo insuficiente ] lance -> ' . $lance . ' : saldo -> ' . ${$currency.'Saldo'}."\n";

                /*
                    - Altera 'status' da "operation" para 'reject'
                    - Altera 'reason' para 'Rejeitado por falta de saldo'
                */

                continue;
            }



            print_r($resultOperation);
            print_r($resultSetExchange);
            print_r($resultSetUser);
            echo "\n".$msg."\n";
            exit();
        }

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
            'data' => $pendingOperations,
        ));

    }

    public function delete($id)
    {
        $this->response->setStatusCode(404);

        return new JsonModel(array(
            'data' => 'not-implemented',
        ));
    }

    // Table "operation"
    public function getOperationTable()
    {
        if(!$this->operationTable){
            $sm = $this->getServiceLocator();
            $this->operationTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->operationTable;
    }

    // Table "stock_exchange"
    public function getExchangeTable(){
        if(!$this->exchangeTable){
            $sm = $this->getServiceLocator();
            $this->exchangeTable = $sm->get('Exchange\Model\ExchangeTable');
        }
        return $this->exchangeTable;
    }

    // Table "user"
    public function getUserTable()
    {
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }

}