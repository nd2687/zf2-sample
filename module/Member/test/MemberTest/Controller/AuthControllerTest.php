<?php
namespace MemberTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AuthControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf2kawano/config/application.config.php'
        );
        parent::setUp();
    }

    public function testLoginformActionCanBeAccessed()
    {
        $this->dispatch('/auth/loginform');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Auth');
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('member');
    }

    public function testLoginActionCanBeAccessed()
    {
        $this->dispatch('/auth/login');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Auth');
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('member');
    }
}
