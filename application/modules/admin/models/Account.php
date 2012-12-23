<?php

class Admin_Model_Account {

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
            $this->setDbTable('Admin_Model_DbTable_Account');
        }
        return $this->_dbTable;
    }

    public function getAllByAgentId($agentId) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array("a" => "consultancy_account"), array("a.*"))
                ->where('a.del="N"');
        $results = $db->fetchAll($select);
        return $results;
    }

    public function add($formData) {
        $formData['due_amount'] = $formData['total_amount'] - $formData['amount'];
        $lastId = $this->getDbTable()->insert($formData);
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        return $lastId;
    }

    public function getDetailById($id) {
        $row = $this->getDbTable()->fetchRow("account_id='$id'");
        if (!$row) {
            throw new Exception("Couldn't fetch such data");
        }
        return $row->toArray();
    }

    public function update($formData, $id) {
        $this->getDbTable()->update($formData, "account_id='$id'");
    }

    public function getDetailByCandidate($id, $agentID) {
        $row = $this->getDbTable()->fetchRow("candidate_id='$id' AND agent_id='$agentID'");
        if (!$row) {
            throw new Exception("Couldn't fetch such data");
        }
        return $row->toArray();
    }
    public function insertIntoAccount($formData){
        $this->getDbTable()->insert($formData);
    }
}