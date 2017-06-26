<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;

class MemberTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function loginIdExists($loginId)
    {
        return $this->findByLoginId($loginId) == true;
    }

    public function findByLoginId($loginId)
    {
        $rowset = $this->tableGateway->select(['login_id' => $loginId]);
        return $rowset->current();
    }

    public function mailAddressExists($mailAddress)
    {
        return $this->findByMailAddress($mailAddress) == true;
    }

    public function findByMailAddress($mailAddress)
    {
        $rowset = $this->tableGateway->select(['mail_address' => $mailAddress]);
        return $rowset->current();
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function saveMember($member)
    {
        $data = array(
            'login_id' => $member->login_id,
            'password'  => $member->password,
            'name' => $member->name,
            'name_kana' => $member->name_kana,
            'mail_address' => $member->mail_address,
            'birthday' => $member->birthday,
            'business_classification_id' => $member->business_classification_id,
        );

        $loginId = $member->login_id;
        if (!$this->loginIdExists($loginId)) {
            $this->tableGateway->insert($data);
        } else {
            throw new \Exception('Fatal error. login_id is already exists.');
        }
    }

    public function deleteMember($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
