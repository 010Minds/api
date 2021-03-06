<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'Stock\Controller\Stock'                    => 'Stock\Controller\StockController',
            'Stock\Controller\StockRest'                => 'Stock\Controller\StockRestController',
            'UserStock\Controller\UserStock'            => 'UserStock\Controller\UserStockController',
            'Exchange\Controller\ExchangeRest'          => 'Exchange\Controller\ExchangeRestController',
            'Operation\Controller\OperationRest'        => 'Operation\Controller\OperationRestController',
            'Cron\Controller\CronRest'                  => 'Cron\Controller\CronRestController',
            'Follows\Controller\FollowsRest'            => 'Follows\Controller\FollowsRestController',
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
            'exchange-rest' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/api/exchange',
                    'defaults' => array(
                        'controller' => 'Exchange\Controller\ExchangeRest',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'exchange-rest-uid' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:uid',
                            'constraints' => array(
                                'uid' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Stock\Controller\StockRest',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'exchange-rest-stock' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/stock',
                                    'constraints' => array(
                                        'uid'     => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Stock\Controller\StockRest',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'exchange-rest-id' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:id',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Exchange\Controller\ExchangeRest',
                            ),
                        ),
                    ),
                ),
            ),

            // Route Cron
            'cron-operation' => array(
                'type'      => 'segment',
                'options'   => array(
                    'route'    => '/api/cron/operation[/]',
                    'defaults' => array(
                        'controller' => 'Cron\Controller\CronRest',
                    ),
                ),
                'may_terminate' => true,
            ),
            /*'cron-action' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/cron/user/:userId/operation[/:opId][/]',
                    'constraints' => array(
                        'userId' => '[0-9]+',
                        'opId' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cron\Controller\CronRest',
                    ),
                ),
            ),*/

            //Route followers
            'followers-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/followers/:uid[/]',
                    'constraints' => array(
                        'uid'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Follows\Controller\FollowsRest',
                    ),
                ),
            ),
            //Route following
            'following-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/following/:uid[/]',
                    'constraints' => array(
                        'uid'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Follows\Controller\FollowsRest',
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