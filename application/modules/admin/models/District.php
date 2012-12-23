<?php

class Admin_Model_District {

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
            $this->setDbTable('Admin_Model_DbTable_District');
        }
        return $this->_dbTable;
    }

    public function getKeysAndValues() {
        $result = $this->getDbTable()->fetchAll();
        $options = array('' => '--Select--');
        foreach ($result as $result) {
            $options[$result['district_id']] = $result['name'];
        }
        return $options;
    }
}

?>
