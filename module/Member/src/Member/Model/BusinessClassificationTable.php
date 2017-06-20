<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;

class BusinessClassificationTable
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

    public function getBusinessClassification($id)
    {
      $id = (int) $id;
      $rowset = $this->tableGateway->select(array('id' => $id));
      $row = $rowset->current();
      if (!$row) {
        throw new \Exception("Could not find row $id");
      }
      return $row;
    }
}
