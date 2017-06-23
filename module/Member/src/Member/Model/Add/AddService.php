<?php

namespace Member\Model\Add;

use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Member\Model\MemberTable;

class AddService
{
    private $memberTable;
    private $mt;

    public function __construct(MemberTable $mt)
    {
        $this->memberTable = $mt;
    }

    public function getInputSpec()
    {
        return  new InputSpec($this);
    }

    public function loginIdExists($value)
    {
        return $this->memberTable->loginIdExists($value);
    }

    public function mailAddressExists($value)
    {
        return $this->memberTable->mailAddressExists($value);
    }
}
