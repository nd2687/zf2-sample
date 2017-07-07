<?php
namespace MemberTest\Model;

use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\Input;
use Zend\Filter\StringTrim;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\StringLength;
use Zend\Validator\Identical;
use Member\Model\Add\InputSpec;
use Member\Model\Add\AddService;
use Member\Model\Add\Validators;
use PHPUnit_Framework_TestCase;

class InputSpecTest extends PHPUnit_Framework_TestCase
{
    private $inputSpec;
    private $service;

    public function setUp()
    {
        $this->service = $this->createMock('Member\Model\Add\AddService');
        $this->inputSpec = new InputSpec($this->service);
    }

    /** ログインID空欄 */
    public function testLoginId()
    {
        $input = $this->inputSpec->loginId();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** ログインID最小文字数 */
    public function testLoginId_CharMin()
    {
        $input = $this->inputSpec->loginId();
        $input->setValue('abc');
        $input->setErrorMessage(null);
        $this->service
            ->method('loginIdExists')
            ->will($this->returnValue(false));
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is less than 4 characters long', current($messages));
    }

    /** ログインID最大文字数 */
    public function testLoginId_CharMax()
    {
        $input = $this->inputSpec->loginId();
        $input->setValue('01234567890123456');
        $input->setErrorMessage(null);
        $this->service
            ->method('loginIdExists')
            ->will($this->returnValue(false));
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is more than 16 characters long', current($messages));
    }

    /** ログインIDの重複 */
    public function testLoginId_Dup()
    {
        $input = $this->inputSpec->loginId();
        $input->setValue('fugafuga');
        $input->setValue('fugafuga');
        $input->setErrorMessage(null);
        $this->service
            ->method('loginIdExists')
            ->will($this->returnValue(true));
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('このログイン名は、既に使用されています。', current($messages));
    }

    /** パスワード空欄 */
    public function testPassword()
    {
        $input = $this->inputSpec->password();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** パスワード最小文字数 */
    public function testPassword_CharMin()
    {
        $input = $this->inputSpec->password();
        $input->setValue('a');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is less than 2 characters long', current($messages));
    }

    /** パスワード最大文字数 */
    public function testPassword_CharMax()
    {
        $input = $this->inputSpec->password();
        $input->setValue('012345678');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is more than 8 characters long', current($messages));
    }

    /** パスワード確認空欄 */
    public function testPasswordConfirmation()
    {
        $input = $this->inputSpec->passwordConfirmation();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** TODO check */
    /** パスワード確認 */
    public function testPasswordConfirmation_CharMin()
    {
        $input = $this->inputSpec->passwordConfirmation();
        $input->setValue('abc');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The two given tokens do not match', current($messages));
    }

    /** 氏名の最小文字数 */
    public function testName()
    {
        $input = $this->inputSpec->name();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** 氏名の最大文字数 */
    public function testName_CharMax()
    {
        $input = $this->inputSpec->name();
        $input->setValue('abcdabcde');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is more than 8 characters long', current($messages));
    }

    /** 氏名（かな）の最小文字数 */
    public function testNameKana()
    {
        $input = $this->inputSpec->nameKana();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** 氏名（かな）の最大文字数 */
    public function testNameKana_CharMax()
    {
        $input = $this->inputSpec->nameKana();
        $input->setValue('abcdabcde');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is more than 8 characters long', current($messages));
    }

    /** メールアドレスの空欄 */
    public function testMailAddress()
    {
        $input = $this->inputSpec->mailAddress();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** メールアドレスの最大文字数 */
    public function testMailAddress_CharMax()
    {
        $input = $this->inputSpec->mailAddress();
        $input->setValue('012345678901234567890123456789012');
        $input->setErrorMessage(null);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('The input is more than 32 characters long', current($messages));
    }

    /** メールアドレスの重複チェック */
    public function testMailAddress_Dup()
    {
        $input = $this->inputSpec->mailAddress();
        $input->setValue('fugafuga@hoge.com');
        $input->setValue('fugafuga@hoge.com');
        $input->setErrorMessage(null);
        $this->service
            ->method('mailAddressExists')
            ->will($this->returnValue(true));
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals('このメールアドレスは、既に使用されています。', current($messages));
    }

    /** 生年月日の空チェック */
    public function testBirthday()
    {
        $input = $this->inputSpec->birthday();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }

    /** 業種の空チェック */
    public function testBusinessClassificationId()
    {
        $input = $this->inputSpec->businessClassificationId();
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertCount(1, $messages);
        $this->assertEquals("Value is required and can't be empty", current($messages));
    }
}
