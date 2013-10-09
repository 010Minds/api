<?php
namespace Stock;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;

use Exchange\Model\Exchange;
use Exchange\Model\ExchangeTable;
use Operation\Model\Operation;
use Operation\Model\OperationTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager;
use Zend\View\Model\JsonModel;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent; 

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'UserStock' => __DIR__ . '/src/' . 'UserStock',
                    'Operation' => __DIR__ . '/src/' . 'Operation',
                    'Exchange'  => __DIR__ . '/src/' . 'Exchange',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                # ----
                'Stock\Model\StockTable' => function($sm){
                    $tableGateway = $sm->get('StockTableGateway');
                    $table = new StockTable($tableGateway);
                    return $table;
                },
                'StockTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Stock());
                    return new TableGateway('stock', $dbAdapter, null, $resultSetPrototype);
                },
                # ----
                'UserStock\Model\UserStockTable' => function($sm){
                    $tableGateway = $sm->get('UserStockTableGateway');
                    $table = new UserStockTable($tableGateway);
                    return $table;
                },
                'UserStockTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserStock());
                    return new TableGateway('user_stock', $dbAdapter, null, $resultSetPrototype);
                },

                'Exchange\Model\ExchangeTable' => function($sm){
                    $tableGateway = $sm->get('ExchangeTableGateway');
                    $table = new ExchangeTable($tableGateway);
                    return $table;
                },
                'ExchangeTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Exchange());
                    return new TableGateway('stock_exchange', $dbAdapter, null, $resultSetPrototype);
                },
                # ----
                'Operation\Model\OperationTable' => function($sm){
                    $tableGateway = $sm->get('OperationTableGateway');
                    $table = new OperationTable($tableGateway);
                    return $table;
                },
                'OperationTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Operation());
                    return new TableGateway('operation', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    public function onBootstrap(MvcEvent $e){
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * @var \Zend\ModuleManager\ModuleManager $moduleManager 
         */
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        /**
         * @var \Zend\EventManager\SharedEventManager $sharedEvents 
         */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Application', 'dispatch.error', array($this, 'moduleError'), 100);
    }

    /**
     * Trata as excessões
     * @return json_encode
     */
    public function moduleError($event){ //var_dump($event->getParam('exception') instanceOf \Exception); 
        if($event->isError() && $event->getError() == 'error-exception'){ 
            $jsonModel = new JsonModel(array( 
                'success'      => FALSE, 
                'errorMessage' => $event->getError(),
                'exception'    => $event->getParam('exception')->getMessage(),
                'code'         => $event->getParam('exception')->getCode(),
                'file'         => $event->getParam('exception')->getFile()  
            )); 
            $event->setViewModel($jsonModel); 
            $event->stopPropagation(); 
            return $jsonModel;  
        }
        else{
            return;
        }
    } 
}