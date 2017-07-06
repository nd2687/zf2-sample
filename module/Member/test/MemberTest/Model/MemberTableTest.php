<?php
namespace MemberTest\Model;

use Member\Model\MemberTable;
use Member\Model\Member;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class MemberTableTest extends PHPUnit_Framework_TestCase
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

    /** fetchAllできる */
    public function testFetchAllReturnsAllMembers()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->createMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $memberTable = new MemberTable($mockTableGateway);

        $this->assertSame($resultSet, $memberTable->fetchAll());
    }

    /** ログインIDでselectできる */
    public function testCanRetrieveAnMemberByItsLoginId()
    {
        $member = new Member();
        $member->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Member());
        $resultSet->initialize(array($member));

        $mockTableGateway = $this->createMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('login_id' => self::TEST_ARRAY['login_id']))
                         ->will($this->returnValue($resultSet));

        $memberTable = new MemberTable($mockTableGateway);

        $this->assertSame($member, $memberTable->findByLoginId(self::TEST_ARRAY['login_id']));
    }

    /** メールアドレスでselectできる */
    public function testCanRetrieveAnMemberByItsMailAddress()
    {
        $member = new Member();
        $member->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Member());
        $resultSet->initialize(array($member));

        $mockTableGateway = $this->createMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('mail_address' => self::TEST_ARRAY['mail_address']))
                         ->will($this->returnValue($resultSet));

        $memberTable = new MemberTable($mockTableGateway);

        $this->assertSame($member, $memberTable->findByMailAddress(self::TEST_ARRAY['mail_address']));
    }

    /** mailAddressExistsメソッドの確認 */
    public function testmailAddressExists()
    {
        $member = new Member();
        $member->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Member());
        $resultSet->initialize(array($member));

        $mockTableGateway = $this->createMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('mail_address' => self::TEST_ARRAY['mail_address']))
                         ->will($this->returnValue($resultSet));

        $memberTable = new MemberTable($mockTableGateway);
        $this->assertTrue($memberTable->mailAddressExists(self::TEST_ARRAY['mail_address']));
    }

    /** loginIdExistsメソッドの確認 */
    public function testloginIdExists()
    {
        $member = new Member();
        $member->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Member());
        $resultSet->initialize(array($member));

        $mockTableGateway = $this->createMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('login_id' => self::TEST_ARRAY['login_id']))
                         ->will($this->returnValue($resultSet));

        $memberTable = new MemberTable($mockTableGateway);
        $this->assertTrue($memberTable->loginIdExists(self::TEST_ARRAY['login_id']));
    }
}
