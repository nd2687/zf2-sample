<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class MemberTable extends AbstractTableGateway
{
    /** @var MemberTable $table */
    protected $table;

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
        $this->table = new MemberTable();
        $rowset = $this->table->select(['login_id' => $loginId]);
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
        $this->table = new MemberTable();
        $rowset = $this->table->select(['mail_address' => $mailAddress]);
        return $rowset->current();
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        $this->table = new MemberTable();
        $resultSet = $this->table->select();
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
        $this->table = new MemberTable();
        $select = $this->table->getSql()->select();
        if (!empty($name)) {
            $select->where
                ->like('name', '%' . $name . '%')
                ->or
                ->like('name_kana', '%' . $name . '%');
        }
        if (!empty($from) || !empty($to)) {
            $select->where->between('birthday', $from, $to);
        }
        $resultSet = $this->table->selectWith($select);
        return $resultSet;
    }

    /**
     * @param Member $member
     */
    public function saveMember($member)
    {
        $this->table = new MemberTable();
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
            $this->table->insert($data);
        } else {
            throw new \Exception('Fatal error. login_id is already exists.');
        }
    }

    /**
     * @param String $id
     */
    public function deleteMember($id)
    {
        $this->table = new MemberTable();
        $this->table->delete(array('id' => $id));
    }
}
