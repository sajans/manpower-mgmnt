<?php

class Admin_Model_Company {

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
            $this->setDbTable('Admin_Model_DbTable_Company');
        }
        return $this->_dbTable;
    }

    public function getAll() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("com" => "consultancy_company"), array("com.*"))
                ->joinLeft(array("add" => "consultancy_add_company"), "add.add_company_id=com.add_company_id", array("add.name as company_name"))
                ->joinLeft(array("can" => "consultancy_candidate_category"), "com.ccat_id=can.ccat_id", array("can.name as category"))
                ->where("com.del='N'");
        $result = $db->fetchAll($select);
        return $result;
    }

    public function add($formData) {
        $formData['entered_date'] = date("Y-m-d");
        $number = $formData['number'];
        unset($formData['number']);
        foreach ($number as $key => $value) {
            $formData['ccat_id'] = $key;
            $formData['number'] = $value;
            $lastId = $this->getDbTable()->insert($formData);
        }
        if (!$lastId) {
            throw new Exception("Couldn't insert data into database");
        }
        return $lastId;
    }

    public function getDetailById($id) {
        $row = $this->getDbTable()->fetchRow("company_id='$id'");
        if (!$row) {
            throw new Exception("Couldn't fetch such data");
        }
        return $row->toArray();
    }

    public function update($formData, $id) {
        $this->getDbTable()->update($formData, "company_id='$id'");
    }

    public function delete($id) {
        $data["del"] = "Y";
        try {
            $this->getDbTable()->update($data, "company_id='$id'");
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function getCompanies() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("comp" => "consultancy_company"), array("distinct(comp.add_company_id) as key"))
                ->joinLeft(array("co" => "consultancy_add_company"), "comp.add_company_id=co.add_company_id", array("co.name as value"));
        $results = $db->fetchAll($select);
        return $results;
    }

    public function filterVisaGroup($companyId) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("comp" => "consultancy_company"), array("distinct(comp.visa_group)", "comp.visa_number"))
                ->where("comp.add_company_id=$companyId");
        $results = $db->fetchAll($select);
        return $results;
    }

    public function filterProfession($visaNumber) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(array("comp" => "consultancy_company"), array("comp.ccat_id", "comp.number"))
                ->joinLeft(array("cc" => "consultancy_candidate_category"), "comp.ccat_id=cc.ccat_id", array("cc.name as profession"))
                ->where("comp.visa_number=$visaNumber AND comp.number>0");
        $results = $db->fetchAll($select);
        return $results;
    }

}

?>
