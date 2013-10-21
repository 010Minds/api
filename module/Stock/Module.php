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
use Follows\Model\Follows;
use Follows\Model\FollowsTable;
use Timeline\Model\Timeline;
use Timeline\Model\TimelineTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager;
use Zend\View\Model\JsonModel;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Http\Request as HttpRequest;


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
                    __NAMESPACE__   => __DIR__ . '/src/' . __NAMESPACE__,
                    'UserStock'     => __DIR__ . '/src/' . 'UserStock',
                    'Operation'     => __DIR__ . '/src/' . 'Operation',
                    'Exchange'      => __DIR__ . '/src/' . 'Exchange',
                    'Cron'          => __DIR__ . '/src/' . 'Cron',
                    'Follows'       => __DIR__ . '/src/' . 'Follows',
                    'Timeline'      => __DIR__ . '/src/' . 'Timeline',
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
                # ----
                'Follows\Model\FollowsTable' => function($sm){
                    $tableGateway = $sm->get('FollowsTableGateway');
                    $table = new FollowsTable($tableGateway);
                    return $table;
                },
                'FollowsTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Follows());
                    return new TableGateway('follows', $dbAdapter, null, $resultSetPrototype);
                },
                # ----
                'Timeline\Model\TimelineTable' => function($sm){
                    $tableGateway = $sm->get('TimelineTableGateway');
                    $table = new TimelineTable($tableGateway);
                    return $table;
                },
                'TimelineTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Timeline());
                    return new TableGateway('timeline', $dbAdapter, null, $resultSetPrototype);
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
        $sharedEvents->attach('Zend\Mvc\Application', 'dispatch', array($this, 'threatDispatch'), 99);
        $sharedEvents->attach('Zend\Mvc\Application', 'dispatch.error', array($this, 'threadDispatchError'), 100);
    }

    /**
     * Trata as excepitions da rota correspontende a controller/action
     * @return json_encode
     */
    public function threatDispatch(MvcEvent $event) {
        $currentModel = $event->getResult();
        if($currentModel instanceof JsonModel){
            echo 'aqui';
            return;
        }
        //var_dump($event->getController());
    }

    /**
     * Trata as excessões do tipo dispatch.error
     * @return json_encode
     */
    public function threadDispatchError(MvcEvent $event){
        $model = new JsonModel(array(
            'header' => array(
                'success'      => false,
                'errorMessage' => $event->getError(),
            ),
        ));


        if ($event->isError() && $event->getError() == 'error-exception') {
            /*if ($event->getParam('exception') instanceof \Application\Exception\NotImplementedException) {
                var_dump($event->getParam('exception'));
            }*/
            //$model->ex        = $event->getParam('exception');
            $model->exception = $event->getParam('exception')->getMessage();
            $model->code      = $event->getParam('exception')->getCode();
            $model->file      = $event->getParam('exception')->getFile() ;
        } elseif ($event->isError() && $event->getError() == 'error-router-no-match') {
            $response  = $event->getResponse();
            $exception = $event->getResult();

            $event->getResponse()->setStatusCode(404);

            $model->httpStatus = $event->getResponse()->getStatusCode();
            $model->title      = $event->getResponse()->getReasonPhrase();
        }

        $event->setViewModel($model);
        $event->stopPropagation();

        return $model;
    }
}