<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Operation\Model\OperationStatus;
use Operation\Model\TypeStatus;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Zend\View\Model\JsonModel;

class CronRestController extends AbstractRestfulController
{
	protected $operationTable;
    protected $stockTable;
    protected $exchangeTable;
    protected $userTable;
    protected $userStockTable;

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
        Verifica as operações pendentes
    */
    public function replaceList($data){

        // Busca as Operações Pendentes
        $resultSetOperation = $this->getOperationTable()->getOperationsStatus('pending');

        foreach ($resultSetOperation as $resultOperation) {

            // Busca os dados do Stock
            $resultSetStock = $this->getStockTable()->getStock($resultOperation->stockId);

            // Busca os dados da stock_exchange do stock atual
            $resultSetExchange = $this->getExchangeTable()->getExchange($resultSetStock->stockExchangeId);

            // Verifica a moeda que o stock_exchange está trabalhando
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


            // COMPRA
            if($resultOperation->type == TypeStatus::BUY){

                if(${$currency.'Saldo'} >= $lance){
                    // Usuário possui saldo

                    // Verefica se tem volume o suficiente disponível para compra
                    if($resultSetStock->volume >= $resultOperation->qtd){
                        // Volume disponível

                        // Altera 'status' da "operation" para 'accepted'
                        $data = array(  'status'=> OperationStatus::ACCEPTED,
                                        'reason' => 'Compra efetuada com sucesso.');
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                        // Desconta o valor da compra do saldo do usuário
                        $saldoAtualizado = ${$currency.'Saldo'} - $lance;
                        $data = array($currency => $saldoAtualizado);
                        $where = array('id' => $resultSetUser->id);
                        $this->getUserTable()->updateUser($data, $where);

                        // Diminui o volume comprado do Stock
                        $volumeAtualizado = $resultSetStock->volume - $resultOperation->qtd;
                        $data = array('volume' => $volumeAtualizado);
                        $where = array('id' => $resultSetStock->id);
                        $this->getStockTable()->updateStock($data, $where);

                        // Inclui Stock em User-stock
                        date_default_timezone_set('America/Sao_Paulo');
                        $dataAtual = date('Y/m/d H:i:s');

                        $data      = array( 'user_id' => $resultOperation->userId,
                                            'stock_id' => $resultOperation->stockId,
                                            'qtd' => $resultOperation->qtd,
                                            'value' => $resultOperation->value,
                                            'create_date' => $dataAtual,
                                            );

                        $userStock = new UserStock;
                        $userStock->exchangeArray($data);

                        $this->getUserStockTable()->saveUserStock($userStock);

                    }
                    else{
                        // Volume indisponível

                        // Altera 'status' da "operation" para 'rejected' e informa o motivo
                        $data = array(  'status'=> OperationStatus::REJECTED,
                                        'reason' => 'Compra rejeitada: Volume indisponível para compra.');
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);
                    }


                }
                else{
                    // Usuário NÃO possui saldo

                    // Altera 'status' da "operation" para 'rejected' e informa o motivo
                    $data = array(  'status'=> OperationStatus::REJECTED,
                                    'reason' => 'Compra rejeitada: Saldo insuficiente.');
                    $where = array('id'=> $resultOperation->id);
                    $this->getOperationTable()->updateOperation($data, $where);
                }
            }
            // VENDA
            else{

                // Verifica se o valor para venda é menor ou igual ao valor de mercado
                if($resultOperation->value <= $resultSetStock->current){

                    // Retorna a quantidade do stock atual para o usuário atual
                    $resultStocksOfUser = $this->getUserStockTable()->getStocksOfUser($resultOperation->userId, $resultOperation->stockId);
                    $qtdStockToSell = 0;
                    $StocksOfUser = array();
                    $i=0;
                    foreach($resultStocksOfUser as $resultStockOfUser){

                        $StocksOfUser[$i]['id'] = $resultStockOfUser->id;
                        $StocksOfUser[$i]['user_id'] = $resultStockOfUser->userId;
                        $StocksOfUser[$i]['stock_id'] = $resultStockOfUser->stockId;
                        $StocksOfUser[$i]['qtd'] = $resultStockOfUser->qtd;

                        // Soma a quantidade disponível do stock atual
                        $qtdStockToSell += $resultStockOfUser->qtd;

                        $i++;
                    }

                    // Verifica se a quantidade de Stock, que o usuário possui, é maior ou igual a quantidade que colocou a venda
                    if($qtdStockToSell >= $resultOperation->qtd){

                        // Inclui o valor da venda ao saldo do usuário
                        $saldoAtualizado = ${$currency.'Saldo'} + $lance;
                        $data = array($currency => $saldoAtualizado);
                        $where = array('id' => $resultSetUser->id);
                        $this->getUserTable()->updateUser($data, $where);

                        // Retorna o volume vendido ao Stock
                        $volumeAtualizado = $resultSetStock->volume + $resultOperation->qtd;
                        $data = array('volume' => $volumeAtualizado);
                        $where = array('id' => $resultSetStock->id);
                        $this->getStockTable()->updateStock($data, $where);

                        // Tira o volume vendido do Stock do usuário
                        foreach ($StocksOfUser as $StockOfUser) {

                            // Se a quantidade a venda for maior ou igual a quantidade do stock atual, decrementa e exclui estoque
                            if($resultOperation->qtd >= $StockOfUser['qtd']){

                            }
                            // Se a quantidade a venda for menor que a quantidade do stock atual, decrementa e atualiza estoque
                            else{

                            }

                        }

                        exit;

                        // Altera 'status' da "operation" para 'accepted'
                        $data = array(  'status'=> OperationStatus::ACCEPTED,
                                        'reason' => 'Venda efetuada com sucesso.');
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                    }
                    else{
                        // Altera 'status' da "operation" para 'reject'
                        $data = array(  'status'=> OperationStatus::REJECTED,
                                        'reason' => 'Venda rejeitada: Volume colocado a venda é menor que o volume disponível no estoque do usuário.');
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);
                    }

                }
                else{
                    // Altera 'status' da "operation" para 'rejected' e informa o motivo
                    $data = array(  'status'=> OperationStatus::REJECTED,
                                    'reason' => 'Venda rejeitada: Valor superior ao valor de mercado.');
                    $where = array('id'=> $resultOperation->id);
                    $this->getOperationTable()->updateOperation($data, $where);
                }

            }



        }



        return new JsonModel(array(
            'data' => "",
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

    // Table "stock"
    public function getStockTable()
    {
        if(!$this->stockTable){
            $sm = $this->getServiceLocator();
            $this->stockTable = $sm->get('Stock\Model\StockTable');
        }
        return $this->stockTable;
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

    // Table "user_stock"
    public function getUserStockTable()
    {
        if(!$this->userStockTable){
            $sm = $this->getServiceLocator();
            $this->userStockTable = $sm->get('UserStock\Model\UserStockTable');
        }
        return $this->userStockTable;
    }

}