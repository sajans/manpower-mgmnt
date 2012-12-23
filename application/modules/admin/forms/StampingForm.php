<?php

class Admin_Form_StampingForm extends Zend_Form {

    public function init() {
        $stampingid = new Zend_Form_Element_Hidden("stamping_id");

        $medicalmodel = new Admin_Model_Medical();
        $option = $medicalmodel->getMedicallyApproved();

        $candidate_name = new Zend_Form_Element_Select("candidate_id");
        $candidate_name->setLabel("Candidate Name")
                ->addMultiOptions($option)
                ->setAttribs(array('class' => 'form-select'));
//
//        $medicalOption = $medicalmodel->getKeysAndValues();
//        var_dump($medicalOption);exit;
//
//        $medical_name = new Zend_Form_Element_Select("medical_id");
//        $medical_name->setLabel("Medical Name")
//                ->addMultiOptions($medicalOption)
//                ->setAttribs(array('class' => 'form-select'));

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $this->addElements(array(
            $stampingid,
            $candidate_name,
            $submit));
    }

}

