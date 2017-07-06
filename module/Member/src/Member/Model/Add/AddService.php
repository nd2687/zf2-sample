<?php
namespace Member\Model\Add;

use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Member\Model\MemberTable;
use Member\Model\PrememberTable;

class AddService
{
    /** @var PrememberTable $prememberTable */
    private $prememberTable;
    /** @var MemberTable $memberTable */
    private $memberTable;
    /** @var PrememberTable $pmt */
    private $pmt;
    /** @var MemberTable $mt */
    private $mt;

    /**
     * @param PrememberTable $pmt
     * @param MemberTable $mt
     */
    public function __construct(PrememberTable $pmt, MemberTable $mt)
    {
        $this->prememberTable = $pmt;
        $this->memberTable = $mt;
    }

    /**
     * @return InputSpec
     */
    public function getInputSpec()
    {
        return  new InputSpec($this);
    }

    /**
     * @param String $value ログインID
     * @return bool
     */
    public function loginIdExists($value)
    {
        if ($this->prememberTable->loginIdExists($value) || $this->memberTable->loginIdExists($value)) {
            return true;
        }
        return false;
    }

    /**
     * @param String $value メールアドレス
     * @return bool
     */
    public function mailAddressExists($value)
    {
        if ($this->prememberTable->mailAddressExists($value) || $this->memberTable->mailAddressExists($value)) {
            return true;
        }
        return false;
    }
}
