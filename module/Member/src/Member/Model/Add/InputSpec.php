<?php
namespace Member\Model\Add;

use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\Input;
use Zend\Filter\StringTrim;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\StringLength;
use Zend\Validator\Identical;

class InputSpec implements InputFilterProviderInterface
{
    /** @var AddService $service */
    private $service;

    /**
     * @param AddService $service
     */
    public function __construct(AddService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Input
     */
    public function loginId()
    {
        $input = new Input('login_id');
        $input->getValidatorChain()
            ->attach(new StringLength(['min'=> 4, 'max' => 16]))
            ->attach(new Alnum())
            ->attach(
                Validators::callback(function ($value) {
                    return !$this->service->loginIdExists($value);
                })->setMessage('このログイン名は、既に使用されています。')
            );

        return $input;
    }

    /**
     * @return Array
     */
    public function getInputFilterSpecification()
    {
        return [
            $this->loginId(),
            $this->password(),
            $this->passwordConfirmation(),
            $this->name(),
            $this->nameKana(),
            $this->mailAddress(),
            $this->birthday(),
            $this->businessClassificationId()
        ];
    }

    /**
     * @return Input
     */
    public function password()
    {
        $input = new Input('password');
        $input->getValidatorChain()
            ->attach(new StringLength(['min'=> 2, 'max' => 8]));
        return $input;
    }

    /**
     * @return Input
     */
    public function passwordConfirmation()
    {
        $input = new Input('password_confirmation');
        $input->getValidatorChain()
            ->attach(new Identical(['token' => 'password']));
        return $input;
    }

    /**
     * @return Input
     */
    public function name()
    {
        $input = new Input('name');
        $input->getValidatorChain()
            ->attach(new StringLength(['max'=> 8]));
        return $input;
    }

    /**
     * @return Input
     */
    public function nameKana()
    {
        $input = new Input('name_kana');
        $input->getValidatorChain()
            ->attach(new StringLength(['max'=> 8]));
        return $input;
    }

    /**
     * @return Input
     */
    public function mailAddress()
    {
        $input = new Input('mail_address');
        $input->getValidatorChain()
            ->attach(new StringLength(['max'=> 32]))
            ->attach(
                Validators::callback(function ($value) {
                    return !$this->service->mailAddressExists($value);
                })->setMessage('このメールアドレスは、既に使用されています。')
            );
        return $input;
    }

    /**
     * @return Input
     */
    public function birthday()
    {
        $input = new Input('birthday');
        return $input;
    }

    /**
     * @return Input
     */
    public function businessClassificationId()
    {
        $input = new Input('business_classification_id');
        return $input;
    }
}
