<?php

class Admin_Form_CompanyNameForm extends Zend_Form {

    public function init() {
        $add_company_id = new Zend_Form_Element_Hidden("add_company_id");

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Name of Company")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $id_number = new Zend_Form_Element_Text("id_number");
        $id_number->setLabel("ID Number")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = array(
            $add_company_id,
            $name,
            $id_number,
            $submit);

        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-item')),
            array('Label', array('tag' => 'div')),
        ));
        $submit->removeDecorator("label");
    }

}