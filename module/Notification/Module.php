<?php
namespace Notification;

use Notification\Model\Notification;
use Notification\Model\NotificationTable;

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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
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
                'Notification\Model\NotificationTable' => function($sm){
                    $tableGateway = $sm->get('NotificationTableGateway');
                    $table = new NotificationTable($tableGateway);
                    return $table;
                },
                'NotificationTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notification());
                    return new TableGateway('notification', $dbAdapter, null, $resultSetPrototype);
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