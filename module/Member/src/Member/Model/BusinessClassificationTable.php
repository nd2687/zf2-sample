<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;

class BusinessClassificationTable
{
    /** @var TableGateway $tableGateway */
    protected $tableGateway;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @param String $id
     * @return BusinessClassification
     */
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
