<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'Stock\Controller\Stock'             => 'Stock\Controller\StockController',
            'Stock\Controller\StockRest'         => 'Stock\Controller\StockRestController',
            'UserStock\Controller\UserStock'     => 'UserStock\Controller\UserStockController',
            'UserStock\Controller\UserStockRest' => 'UserStock\Controller\UserStockRestController',
            'Exchange\Controller\ExchangeRest'   => 'Exchange\Controller\ExchangeRestController',
            'Operation\Controller\OperationRest' => 'Operation\Controller\OperationRestController',
        ),
    ),

    # definir rotas
    'router' => array(
        'routes' => array(
            // Route Stock
            'stock' => array(
                'type'      => 'segment',
                'options'   => array(
                    'route'    => '/stock[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Stock\Controller\Stock',
                        'action'     => 'index',
                    ),
                ),
            ),
            'stock-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/stock[/:id][/]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Stock\Controller\StockRest',
                    ),
                ),
            ),
            // Route UserStock
            'user-stock' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user-stock[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserStock\Controller\UserStock',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user-stock-rest' => array(
                'type'    => 'segment',
                'options' => array(
<<<<<<< HEAD
                    'route'    => '/api/user/:id/stock[/]',
=======
                    'route'    => '/api/user/:uid/:my-stock[/:id][/]',
>>>>>>> cab7a7f4bd0612007d066f78bb1d81c054ab2502
                    'constraints' => array(
                        'uid'     => '[0-9]+',
                        'id'      => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserStock\Controller\UserStockRest',
                    ),
                ),
            ),
            'exchange-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/exchange[/:id][/]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Exchange\Controller\ExchangeRest',
                    ),
                ),
            ),
            'exchange-stock-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/exchange/:uid/:stock[/]',
                    'constraints' => array(
                        'uid'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Stock\Controller\StockRest',
                    ),
                ),
            ),
            // Route Operation
            'operation-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/user/:userId/operation[/]',
                    'constraints' => array(
                        'userId' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\OperationRest',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    'operation-option' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => ':option[/:type]',
                            'constraints' => array(
                                'type' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'option' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Operation\Controller\OperationRest',
                            ),
                        ),
                    ),

                    'operation-id' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => ':id[/]',
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