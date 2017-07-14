<?php
namespace MemberTest\Model;

use Member\Model\MemberTable;
use Member\Model\Member;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class MemberTableTest extends AbstractHttpControllerTestCase
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
        'business_classification_id'  => '1'
    ];

    /** @var MemberTable $memberTable */
    private $memberTable;

    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/zf2kawano/config/application.config.php'
        );
        $serviceManager = $this->getApplicationServiceLocator();
        $this->memberTable = $serviceManager->get('Member\Model\MemberTable');
        $this->memberTable->delete([]);
        $this->memberTable->saveMember((object)self::TEST_ARRAY);
        parent::setUp();
    }

    private function createSequentialData($index)
    {
        $ary                 = self::TEST_ARRAY;
        $ary['login_id']     = $ary['login_id'].(string)$index;
        $ary['mail_address'] = $ary['mail_address'].(string)$index;
        return (object)$ary;
    }

    /** テストデータベースにインサートしてログインIDとメールアドレスが存在しているか */
    public function testLoginIdExistsAndMailAddressExistsWhenInsertData()
    {
        $int = $this->memberTable->fetchAll()->count();
        $int = $int + 1;
        $this->memberTable->saveMember(self::createSequentialData($int));

        $this->assertTrue($this->memberTable->loginIdExists(self::TEST_ARRAY['login_id'].(string)$int));
        $this->assertFalse($this->memberTable->loginIdExists('not-exist-id'));
        $this->assertTrue($this->memberTable->mailAddressExists(self::TEST_ARRAY['mail_address'].(string)$int));
        $this->assertFalse($this->memberTable->mailAddressExists('not-exist-mail'));
    }

    /** fetchAllできる */
    public function testFetchAllReturnsAllMembers()
    {
        $this->assertSame(1, $this->memberTable->fetchAll()->count());
    }

    /** ログインIDでselectできる */
    public function testCanRetrieveAnMemberByItsLoginId()
    {
        $this->assertSame(self::TEST_ARRAY['login_id'], $this->memberTable->findByLoginId(self::TEST_ARRAY['login_id'])->login_id);
    }

    /** メールアドレスでselectできる */
    public function testCanRetrieveAnMemberByItsMailAddress()
    {
        $this->assertSame(self::TEST_ARRAY['login_id'], $this->memberTable->findByMailAddress(self::TEST_ARRAY['mail_address'])->login_id);
    }

    /** mailAddressExistsメソッドの確認 */
    public function testmailAddressExists()
    {
        $this->assertTrue($this->memberTable->mailAddressExists(self::TEST_ARRAY['mail_address']));
    }

    /** loginIdExistsメソッドの確認 */
    public function testloginIdExists()
    {
        $this->assertTrue($this->memberTable->loginIdExists(self::TEST_ARRAY['login_id']));
    }
}
