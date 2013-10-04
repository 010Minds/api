<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
            'User\Controller\UserRest' => 'User\Controller\UserRestController',
        ),
    ),

    # definir rotas
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'      => 'segment',
                'options'   => array(
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/user[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\UserRest',
                    ),
                ),
            ),
        ),
    ),

    # definir layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);