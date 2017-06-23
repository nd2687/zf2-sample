<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Member\Controller\Member' => 'Member\Controller\MemberController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'member' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/member[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Member\Controller\Member',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        // 'invokables' => [
        //     'AddService' => \Member\Model\Add\AddService::class,
        // ],
    ),

    'view_manager' => array(
        // 'template_map' => array(
        //     'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
        // ),
        'template_path_stack' => array(
            'member' => __DIR__ . '/../view',
        ),
    ),
    //
    // 'module_layouts' => array(
    //   'Member' => array(
    //     'default' => 'layout/layout',
    //     'check' => 'layout/layout'
    //   )
    // ),
);
