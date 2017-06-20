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

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    // public function getMember($id)
    // {
    //     $id  = (int) $id;
    //     $rowset = $this->tableGateway->select(array('id' => $id));
    //     $row = $rowset->current();
    //     if (!$row) {
    //         throw new \Exception("Could not find row $id");
    //     }
    //     return $row;
    // }

    // public function saveMember(Member $member)
    // {
    //     $data = array(
    //         'login_id' => $member->login_id,
    //         'password'  => $member->password,
    //         'name' => $member->name,
    //         'name_kana' => $member->name_kana,
    //         'mail_address' => $member->mail_address,
    //         'birthday' => $member->birthday,
    //         'business_classification_id' => $member->business_classification_id,
    //     );
    //
    //     $id = (int)$member->id;
    //     if ($id == 0) {
    //         $this->tableGateway->insert($data);
    //     } else {
    //         if ($this->getMember($id)) {
    //             $this->tableGateway->update($data, array('id' => $id));
    //         } else {
    //             throw new \Exception('Form id does not exist');
    //         }
    //     }
    // }
    //
    // public function deleteMember($id)
    // {
    //     $this->tableGateway->delete(array('id' => $id));
    // }
}
