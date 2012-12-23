<?php

class Admin_Form_InterviewForm extends Zend_Form {

    public function init() {
        $interviewID = new Zend_Form_Element_Hidden("interview_id");
        $option = array(
            'P' => 'Pass',
            'F' => 'Fail',
            'H' => 'Hold'
        );

        $select_box = new Zend_Form_Element_Select('status');
        $select_box->setLabel('Select Box')
                ->setAttribs(array('class' => 'form-select'))
                ->addMultiOptions($option)
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = (array(
            $interviewID,
            $select_box,
            $submit));
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}