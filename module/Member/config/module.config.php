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

    'view_manager' => array(
        'template_path_stack' => array(
            'member' => __DIR__ . '/../view',
        ),
    ),
);
