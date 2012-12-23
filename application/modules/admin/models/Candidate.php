<?php

class Admin_Model_Candidate {

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
            $this->setDbTable('Admin_Model_DbTable_Candidate');
        }
        return $this->_dbTable;
    }

    public function getAll() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_candidate"), array("ccan.*"))
                ->joinLeft(array("dis" => "consultancy_district"), "ccan.district_id=dis.district_id", array("dis.name as district_name"))
                ->where("ur.del='N'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function add($formData) {
        $formData['entered_date'] = date("Y-m-d");
        $lastId = $this->getDbTable()->insert($formData);
        $accountModel = new Admin_Model_Account();
        $data = array(
            'candidate_id' => $lastId,
            'agent_id' => $formData['agent_id']
        );
        $accountModel->insertIntoAccount($data);
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        return $lastId;
    }

    public function getDetailById($id) {
        $row = $this->getDbTable()->fetchRow("candidate_id='$id'");
        if (!$row) {
            throw new Exception("Couldn't fetch such data");
        }
        return $row->toArray();
    }

    public function update($formData, $id) {
        $this->getDbTable()->update($formData, "candidate_id='$id'");
    }

    public function delete($id) {
        $data["del"] = "Y";
        try {
            $this->getDbTable()->update($data, "candidate_id='$id'");
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function getKeysAndValues() {
        $result = $this->getDbTable()->fetchAll("del='N'");
        $options = array('' => '--Select--');
        foreach ($result as $result) {
            $options[$result['candidate_id'] . "::" . $result['name'] . "::" . $result['passport_no']] = $result['name'];
        }
        return $options;
    }

    public function getAllCandidate() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_candidate"), array("ccan.*"))
                ->joinLeft(array("d" => "consultancy_add_company"), "ccan.company_name=d.add_company_id", array("d.name as company_name"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=ccan.agent_id", array("a.name as agent_name"))
                ->joinLeft(array("dis" => "consultancy_district"), "ccan.address=dis.district_id", array("dis.name as district_name"))
                ->where("ccan.del='N'");
        $results = $db->fetchAll($select);
        return $results;
    }

    public function listAll() {
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select();
        $select->from(array("c" => "consultancy_candidate"), array("c.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=c.agent_id", array("a.name as agent_name"))
                ->where("c.del='N'");
        $results = $db->fetchAll($select);
        return $results;
    }

    public function getCandidatesByAgentId($agentId) {
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select();
        $select->from(array("c" => "consultancy_candidate"), array("c.*"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=c.agent_id", array("a.name as agent_name"))
                ->joinLeft(array("acc" => "consultancy_account"), "acc.candidate_id=c.candidate_id", array("acc.amount", "acc.due_amount", "acc.account_id", "acc.total_amount"))
                ->where("c.del='N' AND c.agent_id=" . $agentId);
        $results = $db->fetchAll($select);
        return $results;
    }

    public function getAllById($id) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ccan" => "consultancy_candidate"), array("ccan.*"))
                ->joinLeft(array("d" => "consultancy_add_company"), "ccan.company_name=d.add_company_id", array("d.name as company_name"))
                ->joinLeft(array("a" => "consultancy_agent"), "a.agent_id=ccan.agent_id", array("a.name as agent_name"))
                ->joinLeft(array("dis" => "consultancy_district"), "ccan.address=dis.district_id", array("dis.name as district_name"))
                ->where("ccan.del='N' AND ccan.candidate_id = $id");
        $result = $db->fetchAll($select);
        return $result;
    }

}

?>
