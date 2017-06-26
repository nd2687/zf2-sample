<?php

namespace Member\Model\Add;

use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Member\Model\MemberTable;
use Member\Model\PrememberTable;

class AddService
{
    private $memberTable;
    private $mt;

    public function __construct(PrememberTable $pmt)
    {
        $this->prememberTable = $pmt;
    }

    public function getInputSpec()
    {
        return  new InputSpec($this);
    }

    public function loginIdExists($value)
    {
        return $this->prememberTable->loginIdExists($value);
    }

    public function mailAddressExists($value)
    {
        return $this->prememberTable->mailAddressExists($value);
    }
}
