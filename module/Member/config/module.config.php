<?php
return array(
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'member' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/[:controller][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Member\Controller',
                        'controller' => 'Member',
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
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => [
            'Zend\Db\Adapter\AdapterInterface' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ],
    ),

    'controllers' => array(
        'invokables' => array(
            'Member\Controller\Member' => 'Member\Controller\MemberController',
            'Member\Controller\Auth' => 'Member\Controller\AuthController',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.twig',
            'member/index'            => __DIR__ . '/../view/member/index.twig',
            'error/404'               => __DIR__ . '/../view/error/404.twig',
            'error/index'             => __DIR__ . '/../view/error/index.twig',
        ),
        'template_path_stack' => array(
            'member' => __DIR__ . '/../view',
        ),
    ),

    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);
