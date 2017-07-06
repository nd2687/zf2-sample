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

    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/member/add');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Member');
        $this->assertControllerClass('MemberController');
        $this->assertMatchedRouteName('member');
    }

    public function testConfirmActionCanBeAccessed()
    {
        $this->dispatch('/member/confirm');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Member');
        $this->assertControllerClass('MemberController');
        $this->assertMatchedRouteName('member');
    }

    public function testCompleteActionCanBeAccessed()
    {
        $this->dispatch('/member/complete');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Member');
        $this->assertControllerClass('MemberController');
        $this->assertMatchedRouteName('member');
    }

    /** CompleteアクションでPOSTできる */
    public function testCompleteActionRedirectsAfterValidPost()
    {
        $prememberTableMock = $this->getMockBuilder('Member\Model\PrememberTable')
                                ->disableOriginalConstructor()
                                ->getMock();

        $prememberTableMock->expects($this->once())
                            ->method('savePremember')
                            ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Member\Model\PrememberTable', $prememberTableMock);

        $postData = array(
            'login_id'                   => 'unittest',
            'name'                       => 'ユニットテスト',
            'name_kana'                  => 'ゆにっとてすと',
            'mail_address'               => "unittest@com",
            'birthday'                   => '2017-07-03',
            'password'                   => 'password',
            'password_confirmation'      => 'password',
            'business_classification_id' => '1'
        );
        $this->dispatch('/member/complete', 'POST', $postData);
        $this->assertResponseStatusCode(200);

        $this->assertNotRedirect();
    }

    /** Prememberアクションが200返す */
    public function testCheckPrememberActionCanBeAccessed()
    {
        $this->dispatch('/member/checkPremember');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Member');
        $this->assertControllerName('Member\Controller\Member');
        $this->assertControllerClass('MemberController');
        $this->assertMatchedRouteName('member');
    }

    /** ログインしてないときリダイレクトする */
    public function testSearchformActionRedirectWhenNotLogin()
    {
        $this->dispatch('/member/searchform');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/auth/loginform');
    }

    /** ログインしてないときリダイレクトする */
    public function testSearchActionRedirectWhenNotLogin()
    {
        $this->dispatch('/member/search');
        $this->assertResponseStatusCode(302);
        $this->assertControllerClass('MemberController');
        $this->assertRedirectTo('/auth/loginform');
    }

    /** ログイン時にSearchform、Search 200返す */
    /** pending */
}
