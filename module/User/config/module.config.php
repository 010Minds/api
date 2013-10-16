<?php

return array(

    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User'                  => 'User\Controller\UserController',
            'User\Controller\UserRest'              => 'User\Controller\UserRestController',
            'UserStock\Controller\UserStockRest'    => 'UserStock\Controller\UserStockRestController',
            'User\Controller\UserPerfilRest'        => 'User\Controller\UserPerfilRestController',
            'User\Controller\UserExchangeStockRest' => 'User\Controller\UserExchangeStockRestController',
            'Operation\Controller\OperationRest'    => 'Operation\Controller\OperationRestController',
            'Follows\Controller\FollowsRest'        => 'Follows\Controller\FollowsRestController',
            'Follows\Controller\FollowingRest'      => 'Follows\Controller\FollowingRestController',
            'Timeline\Controller\TimelineRest'      => 'Timeline\Controller\TimelineRestController',
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
                        'may_terminate' => true,
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
                            'user-rest-profile' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/profile',
                                    'defaults' => array(
                                        'controller' => 'User\Controller\UserPerfilRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'user-rest-profile-id' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route'    => '/:id',
                                            'constraints' => array(
                                                'id'     => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'User\Controller\UserPerfilRest',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'user-rest-exchange' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/exchange',
                                    'defaults' => array(
                                        'controller' => 'User\Controller\UserExchangeStockRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'user-rest-exchange-id' => array(
                                        'type'    => 'segment',
                                        'options' => array(
                                            'route'    => '/:id',
                                            'constraints' => array(
                                                'id'     => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'User\Controller\UserExchangeStockRest',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes'  => array(
                                            'user-rest-exchange-id-mystock' => array(
                                                'type'    => 'literal',
                                                'options' => array(
                                                    'route'    => '/mystock',
                                                    'defaults' => array(
                                                        'controller' => 'User\Controller\UserExchangeStockRest',
                                                    ),
                                                ),
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
                            'user-rest-follow' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'verb'     => 'post',
                                    'route'    => '/follow',
                                    'defaults' => array(
                                        'controller' => 'Follows\Controller\FollowsRest',
                                    ),
                                ),
                            ),
                            'user-rest-unfollow' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'verb'     => 'delete',
                                    'route'    => '/unfollow',
                                    'defaults' => array(
                                        'controller' => 'Follows\Controller\FollowsRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'user-rest-follow-id' => array(
                                        'type' => 'segment',
                                        'options' => array(
                                            'route' => '/:id[/]',
                                            'constraints' => array(
                                                'id' => '[0-9]+',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Follows\Controller\FollowsRest',
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
                            'user-rest-followers' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/followers',
                                    'defaults' => array(
                                        'controller' => 'Follows\Controller\FollowsRest',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'user-rest-followers-pending' => array(
                                        'type'    => 'literal',
                                        'options' => array(
                                            'route'    => '/pending',
                                            'defaults' => array(
                                                'controller' => 'Follows\Controller\FollowsRest',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'user-rest-following' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/following',
                                    'defaults' => array(
                                        'controller' => 'Follows\Controller\FollowingRest',
                                    ),
                                ),
                            ),
                            'user-rest-timeline' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/timeline',
                                    'defaults' => array(
                                        'controller' => 'Timeline\Controller\TimelineRest',
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