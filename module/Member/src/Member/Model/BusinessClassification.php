<?php
namespace Member\Model;

class BusinessClassification
{
    public $id;
    public $name;
    public $parent_id;

    /**
     * @param Array $data
     */
    public function exchangeArray($data)
    {
        $this->id         = (!empty($data['id'])) ? $data['id'] : null;
        $this->name       = (!empty($data['name'])) ? $data['name'] : null;
        $this->parent_id  = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
    }
}
