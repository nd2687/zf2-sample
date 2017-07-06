<?php
namespace Member;

use Member\Model\Member;
use Member\Model\MemberTable;
use Member\Model\Premember;
use Member\Model\PrememberTable;
use Member\Model\BusinessClassification;
use Member\Model\BusinessClassificationTable;
use Member\Model\Add\AddService;
use Member\Model\Mail\MailService;
use Member\Model\Auth as Auth;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Member\Model\MemberTable' =>  function($sm) {
                    $tableGateway = $sm->get('MemberTableGateway');
                    $table = new MemberTable($tableGateway);
                    return $table;
                },
                'MemberTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Member());
                    return new TableGateway('member', $dbAdapter, null, $resultSetPrototype);
                },
                'Member\Model\BusinessClassificationTable' =>  function($sm) {
                    $tableGateway = $sm->get('BusinessClassificationTableGateway');
                    $table = new BusinessClassificationTable($tableGateway);
                    return $table;
                },
                'BusinessClassificationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BusinessClassification());
                    return new TableGateway('business_classification', $dbAdapter, null, $resultSetPrototype);
                },
                'Member\Model\Add\AddService' => function($sm) {
                    $memberTable = $sm->get('Member\Model\PrememberTable');
                    $service = new AddService($memberTable);
                    return $service;
                },
                'Member\Model\PrememberTable' =>  function($sm) {
                    $tableGateway = $sm->get('PrememberTableGateway');
                    $table = new PrememberTable($tableGateway);
                    return $table;
                },
                'PrememberTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Premember());
                    return new TableGateway('premember', $dbAdapter, null, $resultSetPrototype);
                },
                'Member\Model\Mail\MailService' => function () {
                    $service = new MailService();
                    return $service;
                },
            ),
        );
    }
}
