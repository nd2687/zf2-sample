<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;

class PrememberTable
{
    /** @var TableGateway $tableGateway */
    protected $tableGateway;

    /** @param TableGateway $tableGateway */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param String $loginId
     * @return bool
     */
    public function loginIdExists($loginId)
    {
        return $this->findByLoginId($loginId) == true;
    }

    /**
     * @param String $loginId
     * @return Member
     */
    public function findByLoginId($loginId)
    {
        $rowset = $this->tableGateway->select(['login_id' => $loginId]);
        return $rowset->current();
    }

    /**
     * @param String $mailAddress
     * @return bool
     */
    public function mailAddressExists($mailAddress)
    {
        return $this->findByMailAddress($mailAddress) == true;
    }

    /**
     * @param String $mailAddress
     * @return Member
     */
    public function findByMailAddress($mailAddress)
    {
        $rowset = $this->tableGateway->select(['mail_address' => $mailAddress]);
        return $rowset->current();
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function authPremember($loginId, $linkPass)
    {
        $member = $this->findByLoginId($loginId);
        if ($member->link_pass == $linkPass && $this->withinExpiration($member)) {
            return $member;
        } else {
            return NULL;
        }
    }

    public function withinExpiration($member)
    {
        if (date('Y-m-d H:i:sP') <= $member->expired_at) {
            return true;
        } else {
            return false;
        }
    }

    public function getPremember($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePremember(Premember $premember)
    {
        $data = array(
            'login_id' => $premember->login_id,
            'password'  => $premember->password,
            'name' => $premember->name,
            'name_kana' => $premember->name_kana,
            'mail_address' => $premember->mail_address,
            'birthday' => $premember->birthday,
            'business_classification_id' => $premember->business_classification_id,
            'link_pass' => $premember->link_pass,
            'expired_at' => $premember->expired_at,
        );

        $id = (int)$premember->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPremember($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePremember($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
