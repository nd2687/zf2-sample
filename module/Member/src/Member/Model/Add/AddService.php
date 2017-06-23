<?php

namespace Member\Model\Add;

use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddService
{
    public function getInputSpec()
    {
        return  new InputSpec($this);
    }
}
