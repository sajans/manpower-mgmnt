<?php

class Admin_Model_Interview {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Admin_Model_DbTable_Interview');
        }
        return $this->_dbTable;
    }

    public function add($formData) {
        $this->getDbTable()->insert($formData);
    }

    public function getAllFail() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_interview"), array("ccan.*"))
                ->joinLeft(array("dis" => "consultancy_candidate"), "ccan.candidate_id=dis.candidate_id", array("dis.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=dis.agent_id", array("a.name as agent_name"))
                ->where("ccan.status = 'F'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function getAllHold() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_interview"), array("ccan.*"))
                ->joinLeft(array("dis" => "consultancy_candidate"), "ccan.candidate_id=dis.candidate_id", array("dis.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=dis.agent_id", array("a.name as agent_name"))
                ->where("ccan.status = 'H'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function update($id) {
        $data = array("status" => "P");
        $this->getDbTable()->update($data, "candidate_id='$id'");
    }
    public function getKeysAndValues() {
         $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_interview"), array("ccan.*"))
                ->joinLeft(array("dis" => "consultancy_candidate"), "ccan.candidate_id=dis.candidate_id", array("dis.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=dis.agent_id", array("a.name as agent_name"))
                ->where("ccan.status = 'P'");
        $result = $db->fetchAll($select);
        $options = array('' => '--Select--');
        foreach ($result as $result) {
            $options[$result['candidate_id'] . "::" . $result['name'] . "::" . $result['passport_no']] = $result['name'];
        }
        return $options;
    }


}

?>
