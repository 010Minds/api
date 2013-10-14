<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User'               => 'User\Controller\UserController',
            'User\Controller\UserRest'           => 'User\Controller\UserRestController',
            'UserStock\Controller\UserStockRest' => 'UserStock\Controller\UserStockRestController',
            'User\Controller\UserPerfilRest'     => 'User\Controller\UserPerfilRestController',
            'Operation\Controller\OperationRest' => 'Operation\Controller\OperationRestController',
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
                    'route'    => '/api/user',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserRest',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user-rest-uid' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:uid',
                            'constraints' => array(
                                'uid'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'User\Controller\UserRest',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'user-rest-operation' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/operation',
                                    'defaults' => array(
                                        'controller' => 'Operation\Controller\OperationRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'user-rest-operation-option' => array(
                                        'type' => 'segment',
                                        'options' => array(
                                            'route' => '/:option[/:type]',
                                            'constraints' => array(
                                                'type' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'option' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Operation\Controller\OperationRest',
                                            ),
                                        ),
                                    ),
                                    'user-rest-operation-id' => array(
                                        'type' => 'segment',
                                        'options' => array(
                                            'route' => '/:id[/]',
                                            'constraints' => array(
                                                'id' => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Operation\Controller\OperationRest',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'user-rest-mystock' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/mystock',
                                    'defaults' => array(
                                        'controller' => 'UserStock\Controller\UserStockRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'user-rest-mystock-id' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route'    => '/:id',
                                            'constraints' => array(
                                                'id'     => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'UserStock\Controller\UserStockRest',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'user-rest-id' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'User\Controller\UserRest',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'user-rest-profile' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/profile',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'User\Controller\UserPerfilRest',
                                    ),
                                ),
                            ),
                        ),
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