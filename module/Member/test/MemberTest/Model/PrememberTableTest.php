<?php
namespace MemberTest\Model;

use Member\Model\PrememberTable;
use Member\Model\Premember;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class PrememberTableTest extends AbstractHttpControllerTestCase
{
    const TEST_ARRAY = [
        'id'                          => 123,
        'login_id'                    => 'modeltest',
        'password'                    => 'password',
        'password_confirmation'       => 'password',
        'name'                        => 'モデルテスト',
        'name_kana'                   => 'もでるてすと',
        'mail_address'                => 'modeltest@example.com',
        'birthday'                    => '2017-07-04',
        'business_classification_id'  => '1',
    ];

    /** @var PrememberTable $prememberTable */
    private $prememberTable;

    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf2kawano/config/application.config.php'
        );
        $serviceManager = $this->getApplicationServiceLocator();
        $this->prememberTable = $serviceManager->get('Member\Model\PrememberTable');
        $this->prememberTable->delete([]);
        $ary               = self::TEST_ARRAY;
        $ary['link_pass']  = hash('sha256', uniqid(rand(), 1));
        $ary['expired_at'] = date('Y-m-d H:i:sP', strtotime('+2 hour'));
        $this->prememberTable->savePremember((object)$ary);
        parent::setUp();
    }

    /** fetchAllできる */
    public function testFetchAllReturnsAllPremembers()
    {
        $this->assertSame(1, $this->prememberTable->fetchAll()->count());
    }

    /** ログインIDで検索できる */
    public function testCanRetrieveAnPrememberByItsLoginId()
    {
        $this->assertSame(self::TEST_ARRAY['login_id'], $this->prememberTable->findByLoginId(self::TEST_ARRAY['login_id'])->login_id);
    }

    /** メールアドレスで検索できる */
    public function testCanRetrieveAnPrememberByItsMailAddress()
    {
        $this->assertSame(self::TEST_ARRAY['login_id'], $this->prememberTable->findByMailAddress(self::TEST_ARRAY['mail_address'])->login_id);
    }

    /** mailAddressExists機能してるか */
    public function testmailAddressExists()
    {
        $this->assertTrue($this->prememberTable->mailAddressExists(self::TEST_ARRAY['mail_address']));
    }

    /** loginIdExists機能してるか */
    public function testloginIdExists()
    {
        $this->assertTrue($this->prememberTable->loginIdExists(self::TEST_ARRAY['login_id']));
    }
}
