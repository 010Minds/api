<?php
namespace Stock;

use Stock\Model\Stock;
use Stock\Model\StockTable;
use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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

}