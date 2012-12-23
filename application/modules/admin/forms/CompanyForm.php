<?php

class Admin_Form_CompanyForm extends Zend_Form {

    public function init() {
        $company_id = new Zend_Form_Element_Hidden("company_id");

        $companynamemodel = new Admin_Model_CompanyName();
        $options = $companynamemodel->getKeysAndValues();

        $company_name = new Zend_Form_Element_Select("add_company_id");
        $company_name->setLabel("Company Name")
                ->addMultiOptions($options)
                ->setAttribs(array('class' => 'form-select'));

        $id_number = new Zend_Form_Element_Text("id_number");
        $id_number->setLabel("ID Number")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $visa_number = new Zend_Form_Element_Text("visa_number");
        $visa_number->setLabel("Visa Number")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $date = new Zend_Form_Element_Text("date");
        $date->setLabel("Issued Date")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $categorymodel = new Admin_Model_Category();
        $option = $categorymodel->getKeysAndValues();

        $category = new Zend_Form_Element_MultiCheckbox("ccat_id");
        $category->setLabel("Profession")
                ->addMultiOptions($option)
                ->setRequired(true)
                ->setAttribs(array('class' => 'form-checkbox'));


        $submit = new Zend_Form_Element_Submit("submit");
        $formElements = array(
            $company_id,
            $company_name,
            $id_number,
            $visa_number,
            $date,
            $category,
            $submit);

        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}