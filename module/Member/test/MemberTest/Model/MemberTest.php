<?php
namespace MemberTest\Model;

use Member\Model\Member;
use PHPUnit_Framework_TestCase;

class MemberTest extends PHPUnit_Framework_TestCase
{
    public function testMemberInitialState()
    {
        // include '/var/www/zf2kawano/config/application.config.php';
        $member = new Member();

        $this->assertNull($member->id,                         '"id" should initially be null');
        $this->assertNull($member->login_id,                   '"login_id" should initially be null');
        $this->assertNull($member->password,                   '"password" should initially be null');
        $this->assertNull($member->password_confirmation,      '"password_confirmation" should initially be null');
        $this->assertNull($member->name,                       '"name" should initially be null');
        $this->assertNull($member->name_kana,                  '"name_kana" should initially be null');
        $this->assertNull($member->mail_address,               '"mail_address" should initially be null');
        $this->assertNull($member->birthday,                   '"birthday" should initially be null');
        $this->assertNull($member->business_classification_id, '"business_classification_id" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $member = new Member();
        $data = array(
                    'id'                          => 123,
                    'login_id'                    => 'modeltest',
                    'password'                    => 'password',
                    'password_confirmation'       => 'password',
                    'name'                        => 'モデルテスト',
                    'name_kana'                   => 'もでるてすと',
                    'mail_address'                => 'modeltest@example.com',
                    'birthday'                    => '2017-07-04',
                    'business_classification_id'  => '1'
                );

        $member->exchangeArray($data);

        $this->assertSame($data['id'], $member->id, '"id" was not set correctly');
        $this->assertSame($data['login_id'], $member->login_id, '"login_id" was not set correctly');
        $this->assertSame($data['password'], $member->password, '"password" was not set correctly');
        $this->assertSame($data['password_confirmation'], $member->password_confirmation, '"password_confirmation" was not set correctly');
        $this->assertSame($data['name'], $member->name, '"name" was not set correctly');
        $this->assertSame($data['name_kana'], $member->name_kana, '"name_kana" was not set correctly');
        $this->assertSame($data['mail_address'], $member->mail_address, '"mail_address" was not set correctly');
        $this->assertSame($data['birthday'], $member->birthday, '"birthday" was not set correctly');
        $this->assertSame($data['business_classification_id'], $member->business_classification_id, '"business_classification_id" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $member = new Member();

        $member->exchangeArray(array(
                    'id'                          => 123,
                    'login_id'                    => 'modeltest',
                    'password'                    => 'password',
                    'password_confirmation'       => 'password',
                    'name'                        => 'モデルテスト',
                    'name_kana'                   => 'もでるてすと',
                    'mail_address'                => 'modeltest@example.com',
                    'birthday'                    => '2017-07-04',
                    'business_classification_id'  => '1'
                ));

        $member->exchangeArray(array());

        $this->assertNull( $member->id, '"id" should have defaulted to null');
        $this->assertNull( $member->login_id, '"login_id" should have defaulted to null');
        $this->assertNull( $member->password, '"password" should have defaulted to null');
        $this->assertNull( $member->password_confirmation, '"password_confirmation" should have defaulted to null');
        $this->assertNull( $member->name, '"name" should have defaulted to null');
        $this->assertNull( $member->name_kana, '"name_kana" should have defaulted to null');
        $this->assertNull( $member->mail_address , '"mail_address" should have defaulted to null');
        $this->assertNull( $member->birthday, '"birthday" should have defaulted to null');
        $this->assertNull( $member->business_classification_id, '"business_classification_id" should have defaulted to null');
    }
}
