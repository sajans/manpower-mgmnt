<?php

class Admin_Form_SalaryForm extends Zend_Form {

    public function init() {
        $salaryid = new Zend_Form_Element_Hidden("salary_id");

        $staffmodel = new Admin_Model_Staff();
        $option = $staffmodel->getKeysAndValues();

        $staff = new Zend_Form_Element_Select("staff_id");
        $staff->setLabel("Staff")
                ->addMultiOptions($option)
                ->setAttribs(array('class' => 'form-select'));

        $amount = new Zend_Form_Element_Text("amount");
        $amount->setLabel("Amount")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $issued_month = new Zend_Form_Element_Text('issued_month');
        $issued_month->setLabel("Issued Month")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $this->addElements(array(
            $salaryid,
            $staff,
            $amount,
            $issued_month,
            $submit));
    }

}

