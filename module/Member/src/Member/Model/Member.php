<?php
namespace Member\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Member // implements InputFilterAwareInterface
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
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->login_id = (!empty($data['login_id'])) ? $data['login_id'] : null;
        $this->password  = (!empty($data['password'])) ? $data['password'] : null;
        $this->password_confirmation  = (!empty($data['password_confirmation'])) ? $data['password_confirmation'] : null;
        $this->name  = (!empty($data['name'])) ? $data['name'] : null;
        $this->name_kana  = (!empty($data['name_kana'])) ? $data['name_kana'] : null;
        $this->mail_address  = (!empty($data['mail_address'])) ? $data['mail_address'] : null;
        $this->birthday  = (!empty($data['birthday'])) ? $data['birthday'] : null;
        $this->business_classification_id  = (!empty($data['business_classification_id'])) ? $data['business_classification_id'] : null;
    }

    // public function getArrayCopy()
    // {
    //     return get_object_vars($this);
    // }
    //
    // public function setInputFilter(InputFilterInterface $inputFilter)
    // {
    //     throw new \Exception("Not used");
    // }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'login_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name_kana',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'mail_address',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'password_confirmation',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
