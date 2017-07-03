<?php

namespace MemberTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class MemberControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf2kawano/config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/member');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Member');
        $this->assertControllerClass('MemberController');
        $this->assertMatchedRouteName('member');
    }
}
