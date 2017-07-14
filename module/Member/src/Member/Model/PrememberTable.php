<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class PrememberTable extends AbstractTableGateway
{
    /** @var PrememberTable $table */
    protected $table;

    public function __construct()
    {
        $this->table = 'premember';
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
     * @return Premember
     */
    public function findByLoginId($loginId)
    {
        $this->table = new PrememberTable();
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
     * @param String $mailAddress
     * @return Premember
     */
    public function findByMailAddress($mailAddress)
    {
        $this->table = new PrememberTable();
        $rowset = $this->table->select(['mail_address' => $mailAddress]);
        return $rowset->current();
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        $this->table = new PrememberTable();
        $resultSet = $this->table->select();
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
        $this->table = new PrememberTable();
        $id = (int) $id;
        $rowset = $this->table->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePremember($premember)
    {
        $this->table = new PrememberTable();
        $data = array(
            'login_id'                   => $premember->login_id,
            'password'                   => $premember->password,
            'name'                       => $premember->name,
            'name_kana'                  => $premember->name_kana,
            'mail_address'               => $premember->mail_address,
            'birthday'                   => $premember->birthday,
            'business_classification_id' => $premember->business_classification_id,
            'link_pass'                  => $premember->link_pass,
            'expired_at'                 => $premember->expired_at,
        );

        $loginId = $premember->login_id;
        if (!$this->loginIdExists($loginId)) {
            $this->table->insert($data);
        } else {
            throw new \Exception('Fatal error. login_id is already exists.');
        }
    }

    public function deletePremember($id)
    {
        $this->table = new PrememberTable();
        $this->table->delete(array('id' => $id));
    }
}
