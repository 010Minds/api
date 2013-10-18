<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'Notification\Controller\NotificationRest'  => 'Notification\Controller\NotificationRestController',
        ),
    ),

    # definir rotas
    'router' => array(
        'routes' => array(
            //Route Notification
            'notification-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/notification[/]',
                    'defaults' => array(
                        'controller' => 'Notification\Controller\NotificationRest',
                    ),
                ),
            ),
        ),
    ),

    # definir layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);