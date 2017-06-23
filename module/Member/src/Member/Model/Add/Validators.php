<?php

namespace Member\Model\Add;

use Zend\Validator\Callback;

class Validators
{
    public static function callback(callable $callback)
    {
        $validator = new Callback($callback);
        return $validator;
    }
}
