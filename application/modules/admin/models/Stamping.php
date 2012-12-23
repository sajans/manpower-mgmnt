<?php

class Admin_Model_Stamping {

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
            $this->setDbTable('Admin_Model_DbTable_Stamping');
        }
        return $this->_dbTable;
    }

    public function getAll() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ur" => "consultancy_stamping"), array("ur.*"))
                ->joinLeft(array("c" => "consultancy_candidate"), "ur.candidate_id=c.candidate_id", array("c.name as candidate_name", "c.passport_no as passport_no", "c.company_name as company_name"))
                ->joinLeft(array("m" => "consultancy_medical"), "c.candidate_id=m.candidate_id", array("m.visa_no as visa_no", "m.medical_center as medical_center", "m.id_number as id_no"))
                ->where("ur.del='N'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function add($formData) {

        $formData['entered_date'] = date("Y-m-d");
        $lastId = $this->getDbTable()->insert($formData);
        var_dump($formData);
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        return $lastId;
    }

    public function getDetailById($id) {
        $row = $this->getDbTable()->fetchRow("stamping_id='$id'");
        if (!$row) {
            throw new Exception("Couldn't fetch such data");
        }
        return $row->toArray();
    }

    public function update($formData, $id) {
        $this->getDbTable()->update($formData, "stamping_id='$id'");
    }

    public function delete($id) {
        $data["del"] = "Y";
        try {
            $this->getDbTable()->update($data, "stamping_id='$id'");
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function listAll() {
        $db = $this->getDbTable()->getDefaultAdapter();
        $select = $db->select();
        $select->from(array("ur" => "consultancy_stamping"), array("ur.*"))
                ->joinLeft(array("c" => "consultancy_candidate"), "ur.candidate_id=c.candidate_id", array("c.name as candidate_name", "c.passport_no as passport_no", "c.company_name as company_name"))
                ->joinLeft(array("m" => "consultancy_medical"), "c.candidate_id=m.candidate_id", array("m.visa_no as visa_no", "m.medical_center as medical_center", "m.id_no as id_no", "m.mofa_no as mofa_no", "m.gamca_no as gamca_no"))
                ->where("ur.del='N'");
        $results = $db->fetchAll($select);
        return $results;
    }

}

?>
