<?php
namespace Member\Model;

class Member
{
    public $id;
    public $login_id;
    public $password;
    public $password_confirmation;
    public $name;
    public $name_kana;
    public $mail_address;
    public $birthday;
    public $business_classification_id;

    /**
     * @param Array $data
     */
    public function exchangeArray($data)
    {
        $this->id                          = (!empty($data['id'])) ? $data['id'] : null;
        $this->login_id                    = (!empty($data['login_id'])) ? $data['login_id'] : null;
        $this->password                    = (!empty($data['password'])) ? $data['password'] : null;
        $this->password_confirmation       = (!empty($data['password_confirmation'])) ? $data['password_confirmation'] : null;
        $this->name                        = (!empty($data['name'])) ? $data['name'] : null;
        $this->name_kana                   = (!empty($data['name_kana'])) ? $data['name_kana'] : null;
        $this->mail_address                = (!empty($data['mail_address'])) ? $data['mail_address'] : null;
        $this->birthday                    = (!empty($data['birthday'])) ? $data['birthday'] : null;
        $this->business_classification_id  = (!empty($data['business_classification_id'])) ? $data['business_classification_id'] : null;
    }
}
