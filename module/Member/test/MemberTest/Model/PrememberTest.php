<?php
namespace MemberTest\Model;

use Member\Model\Premember;
use PHPUnit_Framework_TestCase;

class PrememberTest extends PHPUnit_Framework_TestCase
{
    /** 初期状態ではNULL */
    public function testPrememberInitialState()
    {
        // include '/var/www/zf2kawano/config/application.config.php';
        $premember = new Premember();

        $this->assertNull($premember->id,                         '"id" should initially be null');
        $this->assertNull($premember->login_id,                   '"login_id" should initially be null');
        $this->assertNull($premember->password,                   '"password" should initially be null');
        $this->assertNull($premember->password_confirmation,      '"password_confirmation" should initially be null');
        $this->assertNull($premember->name,                       '"name" should initially be null');
        $this->assertNull($premember->name_kana,                  '"name_kana" should initially be null');
        $this->assertNull($premember->mail_address,               '"mail_address" should initially be null');
        $this->assertNull($premember->birthday,                   '"birthday" should initially be null');
        $this->assertNull($premember->business_classification_id, '"business_classification_id" should initially be null');
    }

    /** プロパティセット */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $premember = new Premember();
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

        $premember->exchangeArray($data);

        $this->assertSame($data['id'], $premember->id, '"id" was not set correctly');
        $this->assertSame($data['login_id'], $premember->login_id, '"login_id" was not set correctly');
        $this->assertSame($data['password'], $premember->password, '"password" was not set correctly');
        $this->assertSame($data['password_confirmation'], $premember->password_confirmation, '"password_confirmation" was not set correctly');
        $this->assertSame($data['name'], $premember->name, '"name" was not set correctly');
        $this->assertSame($data['name_kana'], $premember->name_kana, '"name_kana" was not set correctly');
        $this->assertSame($data['mail_address'], $premember->mail_address, '"mail_address" was not set correctly');
        $this->assertSame($data['birthday'], $premember->birthday, '"birthday" was not set correctly');
        $this->assertSame($data['business_classification_id'], $premember->business_classification_id, '"business_classification_id" was not set correctly');
    }

    /** 引数なしでexchangeArray実行だとNULL */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $premember = new Premember();

        $premember->exchangeArray(array(
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

        $premember->exchangeArray(array());

        $this->assertNull( $premember->id, '"id" should have defaulted to null');
        $this->assertNull( $premember->login_id, '"login_id" should have defaulted to null');
        $this->assertNull( $premember->password, '"password" should have defaulted to null');
        $this->assertNull( $premember->password_confirmation, '"password_confirmation" should have defaulted to null');
        $this->assertNull( $premember->name, '"name" should have defaulted to null');
        $this->assertNull( $premember->name_kana, '"name_kana" should have defaulted to null');
        $this->assertNull( $premember->mail_address , '"mail_address" should have defaulted to null');
        $this->assertNull( $premember->birthday, '"birthday" should have defaulted to null');
        $this->assertNull( $premember->business_classification_id, '"business_classification_id" should have defaulted to null');
    }
}
