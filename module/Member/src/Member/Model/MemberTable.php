<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class MemberTable extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'member';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
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
        $rowset = $this->select(['login_id' => $loginId]);
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
     * @param String #mailAddress
     * @return Member
     */
    public function findByMailAddress($mailAddress)
    {
        $rowset = $this->select(['mail_address' => $mailAddress]);
        return $rowset->current();
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    /**
     * @param String $name 氏名 or 氏名(かな)
     * @param String $from 生年月日
     * @param String $to 生年月日
     * @return ResultSet
     */
    public function searchMember($name, $from, $to)
    {
        $select = $this->getSql()->select();
        if (!empty($name)) {
            $select->where
                ->like('name', '%' . $name . '%')
                ->or
                ->like('name_kana', '%' . $name . '%');
        }
        if (!empty($from) || !empty($to)) {
            $select->where->between('birthday', $from, $to);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet;
    }

    /**
     * @param Member $member
     */
    public function saveMember($member)
    {
        $data = array(
            'login_id'                   => $member->login_id,
            'password'                   => $member->password,
            'name'                       => $member->name,
            'name_kana'                  => $member->name_kana,
            'mail_address'               => $member->mail_address,
            'birthday'                   => $member->birthday,
            'business_classification_id' => $member->business_classification_id,
        );

        $loginId = $member->login_id;
        if (!$this->loginIdExists($loginId)) {
            $this->insert($data);
        } else {
            throw new \Exception('Fatal error. login_id is already exists.');
        }
    }

    /**
     * @param String $id
     */
    public function deleteMember($id)
    {
        $this->delete(array('id' => $id));
    }
}
