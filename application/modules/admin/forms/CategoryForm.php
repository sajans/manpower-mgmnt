<?php

class Admin_Form_CategoryForm extends Zend_Form {

    public function init() {
        $ccat_id = new Zend_Form_Element_Hidden("ccat_id");

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Category Name")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = array(
            $ccat_id,
            $name,
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