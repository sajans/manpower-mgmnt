<?php

class Admin_Form_OrientationForm extends Zend_Form {

    public function init() {

        $orientationid = new Zend_Form_Element_Hidden("orientation_id");

        $candidatemodel = new Admin_Model_Candidate();
        $option = $candidatemodel->getKeysAndValues();

        $candidate_name = new Zend_Form_Element_Select("candidate_id");
        $candidate_name->setLabel("Candidate Name")
                ->addMultiOptions($option)
                ->setAttribs(array('class' => 'form-select'));

        $amount = new Zend_Form_Element_Text("amount");
        $amount->setLabel("amount")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $paidOption = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );

        $paid = new Zend_Form_Element_Select('paid');
        $paid->setLabel("paid")
                ->addMultiOptions($paidOption)
                ->setRequired(true);

        $orientation_company = new Zend_Form_Element_Text('orientation_company');
        $orientation_company->setLabel("Orienation Company")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = (array(
            $orientationid,
            $candidate_name,
            $amount,
            $paid,
            $orientation_company,
            $submit));
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}

