<?php

return array(
     'view_manager' => array(
         'template_path_stack' => array(
             __DIR__ . '/../view',
         ),
     ),
    'controllers' => array(
         'invokables' => array(
             'Blog\Controller\List' => 'Blog\Controller\ListController'
         )
     ),
    'router' => array(
        'routes' => array(
            'post' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\List',
                        'action'     => 'index',
                    )
                )
            )
        )
    )
);
