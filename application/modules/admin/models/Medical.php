<?php

class Admin_Model_Medical {

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
            $this->setDbTable('Admin_Model_DbTable_Medical');
        }
        return $this->_dbTable;
    }

    public function getAll() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("m" => "consultancy_medical"), array("m.*"))
                ->joinLeft(array("c" => "consultancy_candidate"), "m.candidate_id=c.candidate_id", array("c.name as candidate_name"))
                ->joinLeft(array("add" => "consultancy_add_company"), "m.add_company_id=add.add_company_id", array("add.name as company_name"))
                ->where("m.del='N'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function add($formData) {
        $formData['entered_date'] = date("Y-m-d");
        $lastId = $this->getDbTable()->insert($formData);
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        return $lastId;
    }

    public function getKeysAndValues() {
        $result = $this->getDbTable()->fetchAll("del='N'");
        $options = array('' => '--Select--');
        foreach ($result as $result) {
            $options[$result['medical_id']] = $result['medical_center']; //."---".$result['visa_no']."---".$result['id_no'];
        }
        return $options;
    }

    public function getDetailById($id) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("m" => "consultancy_medical"), array("m.*"))
                ->joinLeft(array("c" => "consultancy_candidate"), "m.candidate_id=c.candidate_id", array("c.name as candidate_name", "c.passport_no as passport_no"))
                ->joinLeft(array("add" => "consultancy_add_company"), "m.add_company_id=add.add_company_id", array("add.name as company_name", "add.id_number as id_number"))
                ->where("m.del='N' AND m.medical_id=" . $id);
        $result = $db->fetchAll($select);
        $row = array();
        if (!$result) {
            throw new Exception("Couldn't fetch such data");
        } else {
            $row = $result[0];
        }

        return $row;
    }

    public function update($formData, $id) {
        $this->getDbTable()->update($formData, "medical_id='$id'");
    }

    public function delete($id) {
        $data["del"] = "Y";
        try {
            $this->getDbTable()->update($data, "medical_id='$id'");
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function getMedicallyApproved() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("m" => "consultancy_medical"))
                ->joinLeft(array("c" => "consultancy_candidate"), "m.candidate_id=c.candidate_id", array("c.candidate_id", "c.name", "c.passport_no"))
                ->joinLeft(array("add" => "consultancy_add_company"), "m.add_company_id=add.add_company_id", array("add.name as company_name", "add.id_number as id_number"))
                ->where("m.del='N'");
        $results = $db->fetchAll($select);
        $option = array('' => "--Select--");
        foreach ($results as $result) {
            $option[$result['candidate_id'] . "::" . $result['passport_no']] = $result['name'];
            $option[$result['add_company_id'] . "::" . $result['add_company_no']] = $result['company_name'];
        }
        return $option;
    }

    public function listAll() {
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select();
        $select->from(array("m" => "consultancy_medical"), array("m.*"))
                ->joinLeft(array("c" => "consultancy_candidate"), "c.candidate_id=m.candidate_id", array("c.name as candidate_name", "c.passport_no as passport_no"))
                ->joinLeft(array("add" => "consultancy_add_company"), "m.add_company_id=add.add_company_id", array("add.name as company_name", "add.id_number as id_number"))
                ->where("m.del='N'");
        $results = $db->fetchAll($select);
        return $results;
    }

}

?>
