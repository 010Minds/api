<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Notification\Model\Notification;
use Notification\Model\NotificationTable;
use Notification\Model\TypeStatus as NotificationTypeStatus;
use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Operation\Model\OperationStatus;
use Operation\Model\TypeStatus as OperationTypeStatus;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;

use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

class CronRestController extends AbstractRestfulController
{
    protected $notificationTable;
	protected $operationTable;
    protected $stockTable;
    protected $exchangeTable;
    protected $userTable;
    protected $userStockTable;

    /*
        Verifica as operações pendentes
    */
    public function replaceList($data)
    {
        // Busca as Operações Pendentes
        $resultSetOperation = $this->getOperationTable()->getOperationsStatus('pending');

        // Lista operações pendentes
        foreach ($resultSetOperation as $resultOperation) {

// echo '  OPERATION (pending)'."\n";
// echo '   - id: '.$resultOperation->id."\n";
// echo '   - user_id: '.$resultOperation->userId."\n";
// echo '   - stock_id: '.$resultOperation->stockId."\n";
// echo '   - qtd: '.$resultOperation->qtd."\n";
// echo '   - value: '.$resultOperation->value."\n";
// echo '   - type: '.$resultOperation->type."\n";
// echo '   - status: '.$resultOperation->status."\n";
// echo '   - reason: '.$resultOperation->reason."\n";
// echo "\n";

            // Busca os dados do Stock
            $resultSetStock = $this->getStockTable()->getStock($resultOperation->stockId);

// echo '  STOCK'."\n";
// echo '   - id: '.$resultSetStock->id."\n";
// echo '   - name: '.$resultSetStock->name."\n";
// echo '   - current: '.$resultSetStock->current."\n";
// echo '   - stock_exchange_id: '.$resultSetStock->stockExchangeId."\n";
// echo '   - volume: '.$resultSetStock->volume."\n";
// echo "\n\n";

            // Busca os dados da stock_exchange do stock atual
            $resultSetExchange = $this->getExchangeTable()->getExchange($resultSetStock->stockExchangeId);

// echo '  STOCK_EXCHANGE'."\n";
// echo '   - id: '.$resultSetExchange->id."\n";
// echo '   - name: '.$resultSetExchange->name."\n";
// echo '   - currency: '.$resultSetExchange->currency."\n";
// echo "\n\n";

            // Busca dados do usuario
            $resultSetUser = $this->getUserTable()->getUser($resultOperation->userId);

// echo '  USER'."\n";
// echo '   - id: '.$resultSetUser->id."\n";
// echo '   - name: '.$resultSetUser->name."\n";
// echo '   - user: '.$resultSetUser->user."\n";
// echo '   - reais: '.$resultSetUser->reais."\n";
// echo '   - dollars: '.$resultSetUser->dollars."\n";
// echo "\n\n";

            // Armazena o saldo do usuario em dollar e real
            $dollarsSaldo = $resultSetUser->dollars;
            $reaisSaldo = $resultSetUser->reais;

            // Calcula o valor do lance
            $lance = $resultOperation->qtd * $resultOperation->value;

// echo '  Valor lance (value * qtd): '.$lance."\n";

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


            // COMPRA
            if($resultOperation->type == OperationTypeStatus::BUY){
// echo '  Tipo de operação: COMPRA'."\n";
// echo '  Saldo usuário: '.${$currency.'Saldo'}."\n";

                // Usuário possui saldo
                if(${$currency.'Saldo'} >= $lance){
// echo '  Saldo status: Saldo Suficiente'."\n";

                    // Verefica se tem volume o suficiente disponível para compra
                    // Volume disponível
                    if($resultSetStock->volume >= $resultOperation->qtd){
// echo '  Volume status: Volume Suficiente'."\n";

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

                        // Altera 'status' da "operation" para 'accepted'
                        $description = 'Compra efetuada com sucesso.';

                        $data = array(  'status'=> OperationStatus::ACCEPTED,
                                        'reason' => $description);
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                        // Salva notificação
                        $arr = array(
                            'user_id' => $resultOperation->userId,
                            'type' => $resultOperation->type,
                            'description' => $resultSetStock->name." (".$description.")",
                        );

                        $notification = new Notification;
                        $notification->exchangeArray($arr);
                        $this->getNotificationTable()->saveNotification($notification);
                    }
                    // Volume indisponível
                    else{

                        // Altera 'status' da "operation" para 'rejected' e informa o motivo
                        $description = 'Compra rejeitada: Volume indisponível para compra.';

                        $data = array(  'status'=> OperationStatus::REJECTED,
                                        'reason' => $description);
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                        // Salva notificação
                        $arr = array(
                            'user_id' => $resultOperation->userId,
                            'type' => $resultOperation->type,
                            'description' => $resultSetStock->name." (".$description.")",
                        );

                        $notification = new Notification;
                        $notification->exchangeArray($arr);
                        $this->getNotificationTable()->saveNotification($notification);

// echo '  Volume status: Volume Insuficiente'."\n";
                    }


                }
                // Usuário NÃO possui saldo
                else{

                    // Altera 'status' da "operation" para 'rejected' e informa o motivo
                    $description = 'Compra rejeitada: Saldo insuficiente.';

                    $data = array(  'status'=> OperationStatus::REJECTED,
                                    'reason' => $description);
                    $where = array('id'=> $resultOperation->id);
                    $this->getOperationTable()->updateOperation($data, $where);

                    // Salva notificação
                    $arr = array(
                        'user_id' => $resultOperation->userId,
                        'type' => $resultOperation->type,
                        'description' => $resultSetStock->name." (".$description.")",
                    );

                    $notification = new Notification;
                    $notification->exchangeArray($arr);
                    $this->getNotificationTable()->saveNotification($notification);

// echo '  Saldo status: Saldo Insuficiente'."\n";
                }
            }
            // VENDA
            else{
// echo '  Tipo de operação: VENDA'."\n";

                // Verifica se o valor para venda é menor ou igual ao valor de mercado
                if($resultOperation->value <= $resultSetStock->current){
// echo '  Status da Venda: Valor de venda menor ou igual ao valor de mercado'."\n";

                    // Retorna os estoques do usuario atual filtrado pelo stock_id atual e some a quantidade total
                    $resultStocksOfUser = $this->getUserStockTable()->getStocksOfUser($resultOperation->userId, $resultOperation->stockId);
                    $qtdStockToSell = 0;
                    $stocksOfUser = array();
                    $i=0;
                    foreach($resultStocksOfUser as $resultStockOfUser){

                        $stocksOfUser[$i]['id'] = $resultStockOfUser->id;
                        $stocksOfUser[$i]['user_id'] = $resultStockOfUser->userId;
                        $stocksOfUser[$i]['stock_id'] = $resultStockOfUser->stockId;
                        $stocksOfUser[$i]['qtd'] = $resultStockOfUser->qtd;

                        // Soma a quantidade disponível do stock atual
                        $qtdStockToSell += $resultStockOfUser->qtd;

                        $i++;
                    }
// echo '  : Quantidade de estoques, do stock_id('.$resultOperation->stockId.') que usuário possui: '.$qtdStockToSell."\n";

                    // Verifica se o usuário tem a quantidade de stock colocado a venda
                    if($qtdStockToSell >= $resultOperation->qtd){
// echo '  : Usuário possui os stocks colocado a venda'."\n";

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
                        foreach ($stocksOfUser as $stockOfUser) {

// echo 'Valor entrada: '.$resultOperation->qtd."\n";

                            if($resultOperation->qtd > 0){
                                // Se a quantidade a venda for maior ou igual a quantidade do stock atual, decrementa e exclui estoque
                                // Se a quantidade a venda for menor que a quantidade do stock atual, decrementa e atualiza estoque
                                $resultOperation->qtd = $this->getUserStockTable()->decrementStockUser($stockOfUser['id'], $resultOperation->qtd);
                            }

// echo 'Valor retorno: '.$resultOperation->qtd."\n";

                        }

                        // Altera 'status' da "operation" para 'accepted'
                        $description = 'Venda efetuada com sucesso.';

                        $data = array(  'status'=> OperationStatus::ACCEPTED,
                                        'reason' => $description);
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                        // Salva notificação
                        $arr = array(
                            'user_id' => $resultOperation->userId,
                            'type' => $resultOperation->type,
                            'description' => $resultSetStock->name." (".$description.")",
                        );

                        $notification = new Notification;
                        $notification->exchangeArray($arr);
                        $this->getNotificationTable()->saveNotification($notification);
                    }
                    else{
// echo '  : Usuário não possui os stocks colocado a venda'."\n";

                        // Altera 'status' da "operation" para 'reject'
                        $description = 'Venda rejeitada: Volume colocado a venda é menor que o volume disponível no estoque do usuário.';

                        $data = array(  'status'=> OperationStatus::REJECTED,
                                        'reason' => $description);
                        $where = array('id'=> $resultOperation->id);
                        $this->getOperationTable()->updateOperation($data, $where);

                        // Salva notificação
                        $arr = array(
                            'user_id' => $resultOperation->userId,
                            'type' => $resultOperation->type,
                            'description' => $resultSetStock->name." (".$description.")",
                        );

                        $notification = new Notification;
                        $notification->exchangeArray($arr);
                        $this->getNotificationTable()->saveNotification($notification);
                    }

                }
                else{
// echo '  Status da Venda: Valor de venda maior que o valor de mercado'."\n";

                    // Altera 'status' da "operation" para 'rejected' e informa o motivo
                    $description = 'Venda rejeitada: Valor superior ao valor de mercado.';

                    $data = array(  'status'=> OperationStatus::REJECTED,
                                    'reason' => $description);
                    $where = array('id'=> $resultOperation->id);
                    $this->getOperationTable()->updateOperation($data, $where);

                    // Salva notificação
                    $arr = array(
                        'user_id' => $resultOperation->userId,
                        'type' => $resultOperation->type,
                        'description' => $resultSetStock->name." (".$description.")",
                    );

                    $notification = new Notification;
                    $notification->exchangeArray($arr);
                    $this->getNotificationTable()->saveNotification($notification);
                }

            }

        }



        return new JsonModel(array(
            'data' => "",
        ));

    }

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

    public function delete($id)
    {
        throw new NotImplementedException("This method not exists");
    }


    // Table "notification"
    public function getNotificationTable()
    {
        if(!$this->notificationTable){
            $sm = $this->getServiceLocator();
            $this->notificationTable = $sm->get('Notification\Model\NotificationTable');
        }
        return $this->notificationTable;
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