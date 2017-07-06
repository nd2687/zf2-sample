<?php
namespace MemberTest\Model;

use Member\Model\PrememberTable;
use Member\Model\Premember;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class PrememberTableTest extends PHPUnit_Framework_TestCase
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
    public function testFetchAllReturnsAllPremembers()
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

        $prememberTable = new PrememberTable($mockTableGateway);

        $this->assertSame($resultSet, $prememberTable->fetchAll());
    }

    /** ログインIDで検索できる */
    public function testCanRetrieveAnPrememberByItsLoginId()
    {
        $premember = new Premember();
        $premember->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Premember());
        $resultSet->initialize(array($premember));

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

        $prememberTable = new PrememberTable($mockTableGateway);

        $this->assertSame($premember, $prememberTable->findByLoginId(self::TEST_ARRAY['login_id']));
    }

    /** メールアドレスで検索できる */
    public function testCanRetrieveAnPrememberByItsMailAddress()
    {
        $premember = new Premember();
        $premember->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Premember());
        $resultSet->initialize(array($premember));

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

        $prememberTable = new PrememberTable($mockTableGateway);

        $this->assertSame($premember, $prememberTable->findByMailAddress(self::TEST_ARRAY['mail_address']));
    }

    /** mailAddressExists機能してるか */
    public function testmailAddressExists()
    {
        $premember = new Premember();
        $premember->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Premember());
        $resultSet->initialize(array($premember));

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

        $prememberTable = new PrememberTable($mockTableGateway);
        $this->assertTrue($prememberTable->mailAddressExists(self::TEST_ARRAY['mail_address']));
    }

    /** loginIdExists機能してるか */
    public function testloginIdExists()
    {
        $premember = new Premember();
        $premember->exchangeArray(self::TEST_ARRAY);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Premember());
        $resultSet->initialize(array($premember));

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

        $prememberTable = new PrememberTable($mockTableGateway);
        $this->assertTrue($prememberTable->loginIdExists(self::TEST_ARRAY['login_id']));
    }
}
