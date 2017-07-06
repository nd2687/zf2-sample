<?php
namespace Member\Model\Add;

use Zend\Validator\Callback;

class Validators
{
    /**
     * @param Callback $callback
     * @return Callback
     */
    public static function callback(callable $callback)
    {
        $validator = new Callback($callback);
        return $validator;
    }
}
