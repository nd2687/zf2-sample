<?php
namespace Member\Form;

use Zend\Form\Form;

class MemberForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('member');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'login_id',
            'type' => 'Text',
            'options' => array(
                'label' => 'ログインID',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'パスワード',
            ),
        ));
        $this->add(array(
            'name' => 'password_confirmation',
            'type' => 'Password',
            'options' => array(
                'label' => 'パスワード(確認)',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => '氏名',
            ),
        ));
        $this->add(array(
            'name' => 'name_kana',
            'type' => 'Text',
            'options' => array(
                'label' => '氏名(かな)',
            ),
        ));
        $this->add(array(
            'name' => 'mail_address',
            'type' => 'Email',
            'options' => array(
                'label' => 'メールアドレス',
            ),
        ));
        $this->add(array(
            'name' => 'birthday',
            'type' => 'Text',
            'options' => array(
                'label' => '生年月日',
            ),
        ));
        $this->add(array(
          'name' => 'business_classification_id',
          'type' => 'Select',
          'attributes' => array(
            'id' => 'business_classification_id',
          ),
            'options' => array(
                'label' => '業種',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => '登録',
                'id' => 'submitbutton',
            ),
        ));
    }
}
